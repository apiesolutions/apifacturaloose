<?php

namespace App\Models\Catalogs;

class UnitType extends ModelCatalog
{
    protected $table = "cat_unit_types";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'active',
        'symbol',
        'description',
    ];
}