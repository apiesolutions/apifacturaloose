<?php
namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\SoapType;
use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyRequest;
use App\Http\Resources\CompanyResource;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function create()
    {
        return view('companies.form');
    }

    public function tables()
    {
        return [
            'soap_sends' => config('tables.system.soap_sends'),
            'soap_types' => SoapType::all()
        ];
    }

    public function record()
    {
        $company = Company::active();
        if ($company) {
            $record = new CompanyResource($company);
        } else {
            $record = null;
        }

        return $record;
    }

    public function store(CompanyRequest $request)
    {
        $id = $request->input('id');
        $company = Company::firstOrNew(['id' => $id]);
        $company->fill($request->all());
        if (!$id) {
            $company->user_id = auth()->id();
        }
        $company->save();

        return [
            'success' => true,
            'message' => 'Empresa actualizada'
        ];
    }

    public function uploadFile(Request $request)
    {
        if ($request->hasFile('file')) {

            $company = Company::active();
            
            $type = $request->input('type');
            $file = $request->file('file');
            $ext = $file->getClientOriginalExtension();
            $name = $type.'_'.$company->number.'.'.$ext;
            
            if (($type === 'logo')) request()->validate(['file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048']);
            
            $file->storeAs(($type === 'logo') ? 'public/uploads/logos' : 'certificates', $name);

            $company->$type = $name;

            $company->save();

            return [
                'success' => true,
                'message' => __('app.actions.upload.success'),
                'name' => $name,
                'type' => $type
            ];
        }
        return [
            'success' => false,
            'message' =>  __('app.actions.upload.error'),
        ];
    }
}