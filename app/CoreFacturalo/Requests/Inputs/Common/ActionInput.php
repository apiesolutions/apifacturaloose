<?php

namespace App\CoreFacturalo\Requests\Inputs\Common;

use App\CoreFacturalo\Requests\Inputs\Functions;

class ActionInput
{
    public static function set($inputs)
    {
        $actions = [];
        if(array_key_exists('actions', $inputs)) {
           if($inputs['actions']) {
               $actions = $inputs['actions'];
           }
        }

        return [
            'send_email' => self::sendEmail($actions, $inputs),
            'send_xml_signed' => self::sendXmlSigned($actions, $inputs),
            'format_pdf' => self::formatPdf($actions, $inputs),
        ];
    }

    private static function sendEmail($actions, $inputs)
    {
        return Functions::valueKeyInArray($actions, 'send_email', false);
    }

    private static function sendXmlSigned($actions, $inputs)
    {
        $send_xml_signed = Functions::valueKeyInArray($actions, 'send_xml_signed', true);
        if(in_array($inputs['type'], ['invoice', 'credit', 'debit'])) {
            if($inputs['group_id'] === '02') {
                return false;
            }
            return $send_xml_signed;
        }

        return true;
    }

    private static function formatPdf($actions, $inputs)
    {
        return Functions::valueKeyInArray($actions, 'format_pdf', 'a4');
    }
}