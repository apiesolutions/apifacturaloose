<?php
namespace App\Http\Controllers;

use App\CoreFacturalo\Facturalo;
use App\CoreFacturalo\Helpers\Storage\StorageDocument;
use App\CoreFacturalo\WS\Zip\ZipFly;
use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentEmailRequest;
use App\Http\Requests\DocumentRequest;
use App\Http\Requests\DocumentVoidedRequest;
use App\Http\Resources\DocumentCollection;
use App\Http\Resources\DocumentResource;
use App\Mail\DocumentEmail;
use App\Models\Catalogs\AffectationIgvType;
use App\Models\Catalogs\ChargeDiscountType;
use App\Models\Catalogs\CurrencyType;
use App\Models\Catalogs\DocumentType;
use App\Models\Catalogs\NoteCreditType;
use App\Models\Catalogs\NoteDebitType;
use App\Models\Catalogs\OperationType;
use App\Models\Catalogs\PriceType;
use App\Models\Catalogs\SystemIscType;
use App\Models\Catalogs\AttributeType;
use App\Models\Company;
use App\Models\Configuration;
use App\Models\Document;
use App\Models\Establishment;
use App\Models\Item;
use App\Models\Person;
use App\Models\Series;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Nexmo\Account\Price;

class DocumentController extends Controller
{
    use StorageDocument;

    public function __construct()
    {
        $this->middleware('input.request:document,web', ['only' => ['store']]);
    }

    public function index()
    {
        $is_client = config('tenant.is_client');

        return view('documents.index', compact('is_client'));
    }

    public function columns()
    {
        return [
            'number' => 'Número',
            'date_of_issue' => 'Fecha de emisión'
        ];
    }

    public function records(Request $request)
    {
        $records = Document::where($request->column, 'like', "%{$request->value}%")
                            ->where('user_id', auth()->id())
                            ->latest();

        return new DocumentCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function create()
    {
        return view('tenant.documents.form');
    }

    public function tables()
    {
        $customers = $this->table('customers');
        $establishments = Establishment::where('id', auth()->user()->establishment_id)->get();// Establishment::all();
        $series = Series::all();
        $document_types_invoice = DocumentType::whereIn('id', ['01', '03'])->get();
        $document_types_note = DocumentType::whereIn('id', ['07', '08'])->get();
        $note_credit_types = NoteCreditType::whereActive()->orderByDescription()->get();
        $note_debit_types = NoteDebitType::whereActive()->orderByDescription()->get();
        $currency_types = CurrencyType::whereActive()->get();
        $operation_types = OperationType::whereActive()->get();
        $discount_types = ChargeDiscountType::whereType('discount')->whereLevel('item')->get();
        $charge_types = ChargeDiscountType::whereType('charge')->whereLevel('item')->get();
        $company = Company::active();
        $document_type_03_filter = config('tenant.document_type_03_filter');

        return compact('customers', 'establishments', 'series', 'document_types_invoice', 'document_types_note',
                       'note_credit_types', 'note_debit_types', 'currency_types', 'operation_types',
                       'discount_types', 'charge_types', 'company', 'document_type_03_filter');
    }

    public function item_tables()
    {
        $items = $this->table('items');
        $categories = [];//Category::cascade();
        $affectation_igv_types = AffectationIgvType::whereActive()->get();
        $system_isc_types = SystemIscType::whereActive()->get();
        $price_types = PriceType::whereActive()->get();
        $operation_types = OperationType::whereActive()->get();
        $discount_types = ChargeDiscountType::whereType('discount')->whereLevel('item')->get();
        $charge_types = ChargeDiscountType::whereType('charge')->whereLevel('item')->get();
        $attribute_types = AttributeType::whereActive()->orderByDescription()->get();

        return compact('items', 'categories', 'affectation_igv_types', 'system_isc_types', 'price_types',
                       'operation_types', 'discount_types', 'charge_types', 'attribute_types');
    }

    public function table($table)
    {
        if ($table === 'customers') {
            $customers = Person::whereType('customers')->orderBy('name')->get()->transform(function($row) {
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
            $items = Item::orderBy('description')->get()->transform(function($row) {
                $full_description = ($row->internal_id)?$row->internal_id.' - '.$row->description:$row->description;
                return [
                    'id' => $row->id,
                    'full_description' => $full_description,
                    'description' => $row->description,
                    'currency_type_id' => $row->currency_type_id,
                    'currency_type_symbol' => $row->currency_type->symbol,
                    'sale_unit_price' => $row->sale_unit_price,
                    'purchase_unit_price' => $row->purchase_unit_price,
                    'unit_type_id' => $row->unit_type_id,
                    'sale_affectation_igv_type_id' => $row->sale_affectation_igv_type_id,
                    'purchase_affectation_igv_type_id' => $row->purchase_affectation_igv_type_id
                ];
            });
            return $items;
        }

        return [];
    }

    public function record($id)
    {
        $record = new DocumentResource(Document::findOrFail($id));

        return $record;
    }

    public function store(DocumentRequest $request)
    {
        $fact = DB::connection('tenant')->transaction(function () use ($request) {
            $facturalo = new Facturalo();
            $facturalo->save($request->all());
            $facturalo->createXmlUnsigned();
            $facturalo->signXmlUnsigned();
            $facturalo->updateHash();
            $facturalo->updateQr();
            $facturalo->createPdf();
            $facturalo->senderXmlSignedBill();

            return $facturalo;
        });

        $document = $fact->getDocument();
        $response = $fact->getResponse();

        return [
            'success' => true,
            'data' => [
                'id' => $document->id,
            ],
        ];
    }

    public function email(DocumentEmailRequest $request)
    {
        $company = Company::active();
        $document = Document::find($request->input('id'));
        $customer_email = $request->input('customer_email');

        Mail::to($customer_email)->send(new DocumentEmail($company, $document));

        return [
            'success' => true
        ];
    }

    public function send($document_id)
    {
        $document = Document::find($document_id);

        $fact = DB::connection('tenant')->transaction(function () use ($document) {
            $facturalo = new Facturalo();
            $facturalo->setDocument($document);
            $facturalo->loadXmlSigned();
            $facturalo->onlySenderXmlSignedBill();
            return $facturalo;
        });

        $response = $fact->getResponse();

        return [
            'success' => true,
            'message' => $response['description'],
        ];
    }

    public function consultCdr($document_id)
    {
        $document = Document::find($document_id);

        $fact = DB::connection('tenant')->transaction(function () use ($document) {
            $facturalo = new Facturalo();
            $facturalo->setDocument($document);
            $facturalo->consultCdr();
        });

        $response = $fact->getResponse();

        return [
            'success' => true,
            'message' => $response['description'],
        ];
    }

    public function sendServer($document_id)
    {
        $document = Document::find($document_id);
        $bearer = config('tenant.token_server');
        $api_url = config('tenant.url_server');
        $client = new Client(['base_uri' => $api_url]);

//        $zipFly = new ZipFly();

        $data_json = (array) $document->data_json;
        $data_json['external_id'] = $document->external_id;
        $data_json['hash'] = $document->hash;
        $data_json['qr'] = $document->qr;
        $data_json['file_xml_signed'] = base64_encode($this->getStorage($document->filename, 'signed'));
        $data_json['file_pdf'] = base64_encode($this->getStorage($document->filename, 'pdf'));

        $res = $client->post('/api/documents_server', [
            'http_errors' => false,
            'headers' => [
                'Authorization' => 'Bearer '.$bearer,
                'Accept' => 'application/json',
            ],
            'form_params' => $data_json
        ]);

        $response = json_decode($res->getBody()->getContents(), true);

        if($response['success']) {
            $document->send_server = true;
            $document->save();
        }

        return $response;
    }

    public function checkServer($document_id)
    {
        $document = Document::find($document_id);
        $bearer = config('tenant.token_server');
        $api_url = config('tenant.url_server');

        $client = new Client(['base_uri' => $api_url]);

        $res = $client->get('/api/document_check_server/'.$document->external_id, [
            'headers' => [
                'Authorization' => 'Bearer '.$bearer,
                'Accept' => 'application/json',
            ],
        ]);

        $response = json_decode($res->getBody()->getContents(), true);

        if($response['success']) {
            $state_type_id = $response['state_type_id'];
            $document->state_type_id = $state_type_id;
            $document->save();

            if($state_type_id === '05') {
                $this->uploadStorage($document->filename, base64_decode($response['file_cdr']), 'cdr');
            }
        }

        return $response;
    }
}