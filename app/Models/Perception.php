<?php

namespace App\Models;

use App\Models\Catalogs\CurrencyType;
use App\Models\Catalogs\DocumentType;
use App\Models\Catalogs\PerceptionType;
use Illuminate\Database\Eloquent\Model;

class Perception extends Model
{
    protected $with = ['user', 'soap_type', 'state_type', 'document_type', 'perception_type', 'currency_type', 'documents'];

    protected $fillable = [
        'user_id',
        'external_id',
        'establishment_id',
        'establishment',
        'soap_type_id',
        'state_type_id',
        'ubl_version',
        'document_type_id',
        'series',
        'number',
        'date_of_issue',
        'time_of_issue',
        'customer_id',
        'customer',
        'perception_type_id',
        'observations',
        'currency_type_id',
        'total_perception',
        'total',

        'legends',

        'filename',
        'hash',

        'has_xml',
        'has_pdf',
        'has_cdr'
    ];

    protected $casts = [
        'date_of_issue' => 'date',
    ];

    public function getEstablishmentAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setEstablishmentAttribute($value)
    {
        $this->attributes['establishment'] = (is_null($value))?null:json_encode($value);
    }

    public function getCustomerAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setCustomerAttribute($value)
    {
        $this->attributes['customer'] = (is_null($value))?null:json_encode($value);
    }

    public function getLegendsAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setLegendsAttribute($value)
    {
        $this->attributes['legends'] = (is_null($value))?null:json_encode($value);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }

    public function soap_type()
    {
        return $this->belongsTo(SoapType::class);
    }

    public function state_type()
    {
        return $this->belongsTo(StateType::class);
    }

    public function document_type()
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id');
    }

    public function perception_type()
    {
        return $this->belongsTo(PerceptionType::class, 'perception_type_id');
    }

    public function currency_type()
    {
        return $this->belongsTo(CurrencyType::class, 'currency_type_id');
    }

    public function documents()
    {
        return $this->hasMany(PerceptionDocument::class);
    }

    public function getNumberFullAttribute()
    {
        return $this->series.'-'.$this->number;
    }

    public function getDownloadExternalXmlAttribute()
    {
        return route('download.external_id', ['model' => 'perception', 'type' => 'xml', 'external_id' => $this->external_id]);
    }

    public function getDownloadExternalPdfAttribute()
    {
        return route('download.external_id', ['model' => 'perception', 'type' => 'pdf', 'external_id' => $this->external_id]);
    }

    public function getDownloadExternalCdrAttribute()
    {
        return route('download.external_id', ['model' => 'perception', 'type' => 'cdr', 'external_id' => $this->external_id]);
    }
}