<?php

namespace App\Models\Catalogs;

class CurrencyType extends ModelCatalog
{
    protected $table = "cat_currency_types";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'active',
        'symbol',
        'description',
    ];
}