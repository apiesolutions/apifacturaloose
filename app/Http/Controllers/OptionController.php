<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Retention;
use App\Models\Summary;
use App\Models\Voided;
use App\Models\Perception;
use App\Models\Dispatch;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    public function create()
    {
        return view('tenant.options.form');
    }

    public function deleteDocuments(Request $request)
    {
        Perception::where('soap_type_id', '01')->delete();
        Dispatch::where('soap_type_id', '01')->delete();

        Summary::where('soap_type_id', '01')->delete();
        Voided::where('soap_type_id', '01')->delete();
        Document::where('soap_type_id', '01')
                ->whereIn('document_type_id', ['07', '08'])->delete();
        Document::where('soap_type_id', '01')->delete();
        Retention::where('soap_type_id', '01')->delete();

        return [
            'success' => true,
            'message' => 'Documentos de prueba eliminados'
        ];
    }
}