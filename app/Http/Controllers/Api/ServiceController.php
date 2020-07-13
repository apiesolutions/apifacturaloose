<?php
namespace App\Http\Controllers\Api;

use App\CoreFacturalo\Services\Dni\Dni;
use App\CoreFacturalo\Services\Extras\ExchangeRate;
use App\CoreFacturalo\Services\Ruc\Sunat;
use App\Http\Controllers\Controller;
use App\Models\Catalogs\Department;
use App\Models\Catalogs\District;
use App\Models\Catalogs\Province;
use App\Models\Document;
use Illuminate\Http\Request;
use Exception;

class ServiceController extends Controller
{
    public function ruc($number)
    {
        $service = new Sunat();
        $res = $service->get($number);
        if ($res) {
            $province_id = Province::idByDescription($res->provincia);
            return [
                'success' => true,
                'data' => [
                    'name' => $res->razonSocial,
                    'trade_name' => $res->nombreComercial,
                    'address' => $res->direccion,
                    'phone' => implode(' / ', $res->telefonos),
                    'department' => ($res->departamento)?:'LIMA',
                    'department_id' => Department::idByDescription($res->departamento),
                    'province' => ($res->provincia)?:'LIMA',
                    'province_id' => $province_id,
                    'district' => ($res->distrito)?:'LIMA',
                    'district_id' => District::idByDescription($res->distrito, $province_id),
                ]
            ];
        } else {
            return [
                'success' => false,
                'message' => $service->getError()
            ];
        }
    }

    public function dni($number)
    {
        $res = Dni::search($number);

        return $res;
    }

    public function documentStatus(Request $request) {
        if($request->has('external_id')) {
            $external_id = $request->input('external_id');
            $request_serie = $request->input('serie_number');
            $serie_number = explode('-', $request_serie);
            $serie = $serie_number[0];
            $number = $serie_number[1];

            if(!$external_id) {
                $document = Document::where('number', $number)
                            ->where('series', $serie)
                            ->first();
            } else {
                $document = Document::where('external_id', $external_id)
                            ->where('number', $number)
                            ->where('series', $serie)
                            ->first();
            }
            
            if(!$document) {
                throw new Exception("El documento con código externo {$external_id} o numero {$request_serie}, no se encuentran registrados o no coinciden.");
            }
            return [
                'success' => true,
                'data' => [
                    'number' => $document->number_full,
                    'filename' => $document->filename,
                    'external_id' => $document->external_id,
                    'status_id' => $document->state_type_id,
                    'status' => $document->state_type->description,
                    'qr' => $document->qr,
                    'number_to_letter' => $document->number_to_letter,
                ],
                'links' => [
                    'xml' => $document->download_external_xml,
                    'pdf' => $document->download_external_pdf,
                    'cdr' => ($document->download_external_cdr)?$document->download_external_cdr:'',
                ],
            ];
        }
    }
}