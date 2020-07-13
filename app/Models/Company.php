<?php

namespace App\Models;

use App\Models\Catalogs\IdentityDocumentType;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $with = ['identity_document_type'];
    protected $fillable = [
        'user_id',
        'identity_document_type_id',
        'number',
        'name',
        'trade_name',
        'soap_send_id',
        'soap_type_id',
        'soap_username',
        'soap_password',
        'soap_url',
        'certificate',
        'logo',
        'user_id',
    ];

    public function identity_document_type()
    {
        return $this->belongsTo(IdentityDocumentType::class, 'identity_document_type_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function active()
    {
        return Company::where('user_id', auth()->id())->first();
    }
}