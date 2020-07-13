<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DispatchItem extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'dispatch_id',
        'item_id',
        'item',
        'quantity',
    ];

    public function getItemAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setItemAttribute($value)
    {
        $this->attributes['item'] = (is_null($value))?null:json_encode($value);
    }
}