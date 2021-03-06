<?php

namespace App\Mail;

use App\CoreFacturalo\Helpers\Storage\StorageDocument;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DocumentEmail extends Mailable
{
    use Queueable, SerializesModels;
    use StorageDocument;

    public $company;
    public $document;

    public function __construct($company, $document)
    {
        $this->company = $company;
        $this->document = $document;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $pdf = $this->getStorage($this->document->filename, 'pdf', $this->company->number);
        $xml = $this->getStorage($this->document->filename, 'signed', $this->company->number);

        return $this->subject('Envio de Comprobante de Pago Electrónico')
                    ->from(config('mail.username'), 'Comprobante electrónico')
                    ->view('templates.email.document')
                    ->attachData($pdf, $this->document->filename.'.pdf')
                    ->attachData($xml, $this->document->filename.'.xml');
    }
}