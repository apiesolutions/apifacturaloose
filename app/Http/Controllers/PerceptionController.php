<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PerceptionRequest;
use App\Http\Resources\PerceptionCollection;
use App\Http\Resources\PerceptionResource;
use App\Models\Customer;
use App\Models\Company;
use App\Models\Establishment;
use App\Models\Series;
use App\Models\Catalogs\CurrencyType;
use App\Models\Catalogs\DocumentType;
use App\Models\Perception;
use App\Models\PerceptionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerceptionController extends Controller
{
    public function index()
    {
        return view('perceptions.index');
    }

    public function columns()
    {
        return [
            'id' => 'Código',
            'number' => 'Número'
        ];
    }

    public function records(Request $request)
    {
        $records = Perception::where($request->column, 'like', "%{$request->value}%")
                            ->orderBy('series')
                            ->orderBy('number', 'desc');

        return new PerceptionCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function tables()
    {
        $user_id = Auth::id();
        $currency_types = CurrencyType::all();
        $customers = $this->table('customers');
        $items = $this->table('items');
        $company = Company::with(['identity_document_type'])->first();
        $establishments = Establishment::all();
        $document_types = DocumentType::all();
        $series = Series::all();

        return compact('user_id', 'currency_types', 'customers', 'items', 'company', 'establishments','document_types', 'series');
    }

    public function item_tables()
    {
        $items = $this->table('items');
        $currency_types = CurrencyType::all();
        $document_types = DocumentType::all();

        return compact('items', 'currency_types', 'document_types');
    }

    public function table($table)
    {
        if ($table === 'customers') {
            $customers = Customer::with(['identity_document_type'])->orderBy('name')->get()->transform(function($row) {
                return [
                    'id' => $row->id,
                    'description' => $row->number.' - '.$row->name,
                    'name' => $row->name,
                    'number' => $row->number,
                    'identity_document_type_id' => $row->identity_document_type_id,
                    'identity_document_type_code' => $row->identity_document_type->code
                ];
            });
            return $customers;
        }
        if ($table === 'items') {
            return PerceptionDetail::all();
        }

        return [];
    }

    public function record($id)
    {
        $record = new PerceptionResource(Perception::findOrFail($id));

        return $record;
    }

    public function setNumber($data)
    {
        $number = $data['number'];
        $series_id = $data['series_id'];
        $document_type_id = $data['document_type_id'];
        $soap_type_id = $data['soap_type_id'];
        if ($data['number'] === '#') {
            $document = Perception::select('number')
                                    ->where('series_id', $series_id)
                                    ->where('document_type_id', $document_type_id)
                                    ->where('soap_type_id', $soap_type_id)
                                    ->orderBy('number', 'desc')
                                    ->first();
             $number = ($document)?(int)$document->number+1:1;
        }
        return $number;
    }

    public function store(PerceptionRequest $request)
    {
        $id = $request->input('id');
        $record = Perception::firstOrNew(['id' => $id]);
        $attributes = $request->all();
        $attributes['number'] = $this->setNumber($attributes);
        $record->fill($attributes);
        $record->save();
        foreach ($attributes['items'] as $detail) {
            $record->details()->create($detail);
        }
        return [
            'success' => true,
            'message' => ($id)?'Percepción editada con éxito':'Percepción registrada con éxito'
        ];
    }

    public function destroy($id)
    {
        $record = Perception::findOrFail($id);
        $record->delete();

        return [
            'success' => true,
            'message' => 'Percepción eliminada con éxito'
        ];
    }
}