<?php
namespace App\Http\Controllers;

use App\CoreFacturalo\Helpers\Storage\StorageDocument;
use App\Http\Controllers\Controller;
use App\CoreFacturalo\Facturalo;
use App\CoreFacturalo\Template;
use App\Models\Company;
use Mpdf\Mpdf;
use Exception;

class DownloadController extends Controller
{
    use StorageDocument;
    
    public function downloadExternal($model, $type, $external_id, $format = null) {
        $model = "App\\Models\\".ucfirst($model);
        $document = $model::where('external_id', $external_id)->first();
        
        if (!$document) throw new Exception("El código {$external_id} es inválido, no se encontro documento relacionado");
        
        if ($format != null) $this->reloadPDF($document, 'invoice', $format);
        
        return $this->download($type, $document);
    }
    
    public function download($type, $document) {
        switch ($type) {
            case 'pdf':
                $folder = 'pdf';
                break;
            case 'xml':
                $folder = 'signed';
                break;
            case 'cdr':
                $folder = 'cdr';
                break;
            default:
                throw new Exception('Tipo de archivo a descargar es inválido');
        }
        
        return $this->downloadStorage($document->filename, $folder, $document->user->company->number);
    }
    
    public function toPrint($model, $external_id, $format = null) {
        $model = "App\\Models\\".ucfirst($model);
        $document = $model::where('external_id', $external_id)->first();
        
        if (!$document) throw new Exception("El código {$external_id} es inválido, no se encontro documento relacionado");
        
        if ($format != null) $this->reloadPDF($document, 'invoice', $format);
        
        $temp = tempnam(sys_get_temp_dir(), 'pdf');
        file_put_contents($temp, $this->getStorage($document->filename, 'pdf'));
        
        return response()->file($temp);
    }
    
    /**
     * Reload PDF
     * @param  ModelTenant $document
     * @param  string $format
     * @return void
     */
    private function reloadPDF($document, $type, $format) {
        (new Facturalo)->createPdf($document, $type, $format);
    }
}
