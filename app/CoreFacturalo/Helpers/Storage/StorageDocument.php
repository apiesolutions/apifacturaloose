<?php

namespace App\CoreFacturalo\Helpers\Storage;

use App\Models\Company;
use Illuminate\Support\Facades\Storage;

trait StorageDocument
{
    protected $_folder;
    protected $_filename;

    public function uploadStorage($filename, $file_content, $file_type, $root = null)
    {
//        $root = Company::active()->number;
        $this->setData($filename, $file_type, $root);
        Storage::disk('tenant')->put($this->_folder.DIRECTORY_SEPARATOR.$this->_filename, $file_content);
    }

    public function downloadStorage($filename, $file_type, $root = null)
    {
//        $root = Company::active()->number;
        $this->setData($filename, $file_type, $root);
        return Storage::disk('tenant')->download($this->_folder.DIRECTORY_SEPARATOR.$this->_filename);
    }

    public function getStorage($filename, $file_type, $root = null)
    {
//        $root = Company::active()->number;
        $this->setData($filename, $file_type, $root);
        return Storage::disk('tenant')->get($this->_folder.DIRECTORY_SEPARATOR.$this->_filename);
    }

    private function setData($filename, $file_type, $root)
    {
        //$root = Company::active()->number;
        $extension = 'xml';
        switch ($file_type) {
            case 'unsigned':
                break;
            case 'signed':
                break;
            case 'pdf':
                $extension = 'pdf';
                break;
            case 'cdr':
                $filename = 'R-'.$filename;
                $extension = 'zip';
                break;
        }
        $this->_filename = $filename.'.'.$extension;
        $this->_folder = ($root)?$root.DIRECTORY_SEPARATOR.$file_type:$file_type;
    }
}