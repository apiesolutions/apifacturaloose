<?php

namespace App\Models\Catalogs;

class AttributeType extends ModelCatalog
{
    protected $table = "cat_attribute_types";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'active',
        'description',
    ];
}