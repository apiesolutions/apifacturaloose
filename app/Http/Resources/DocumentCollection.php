<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DocumentCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function toArray($request)
    {
        return $this->collection->transform(function($row, $key)
        {
            $has_xml = true;
            $has_pdf = true;
            $has_cdr = false;
            $btn_note = false;
            $btn_resend = false;
            $btn_voided = false;
            $btn_consult_cdr = false;

            $affected_document = null;

            if($row->group_id === '01') {
                if($row->state_type_id === '01') {
                    $btn_resend = true;
                }
                if($row->state_type_id === '05') {
                    $has_cdr = true;
                    $btn_note = true;
                    $btn_resend = false;
                    $btn_voided = true;
                    $btn_consult_cdr = true;
                }
                if(in_array($row->document_type_id, ['07', '08'])) {
                    $btn_note = false;
                }
            }
            if($row->group_id === '02') {
                if($row->state_type_id === '05') {
                    $btn_note = true;
                    $btn_voided = true;
                }
                if(in_array($row->document_type_id, ['07', '08'])) {
                    $btn_note = false;
                }
            }

            return [
                'id' => $row->id,
                'group_id' => $row->group_id,
                'soap_type_id' => $row->soap_type_id,
                'date_of_issue' => $row->date_of_issue->format('Y-m-d'),
                'document_type_short' => $row->document_type->short,
                'number' => $row->number_full,
                'customer_name' => $row->customer->name,
                'customer_number' => $row->customer->identity_document_type->description.' '.$row->customer->number,
                'total_free' => $row->total_free,
                'total_unaffected' => $row->total_unaffected,
                'total_exonerated' => $row->total_exonerated,
                'total_taxed' => $row->total_taxed,
                'total_igv' => $row->total_igv,
                'total' => $row->total,
                'state_type_id' => $row->state_type_id,
                'state_type_description' => $row->state_type->description,
                'has_xml' => $has_xml,
                'has_pdf' => $has_pdf,
                'has_cdr' => $has_cdr,
                'download_external_xml' => $row->download_external_xml,
                'download_external_pdf' => $row->download_external_pdf,
                'download_external_cdr' => $row->download_external_cdr,
                'created_at' => $row->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $row->updated_at->format('Y-m-d H:i:s'),
            ];
        });
    }
}