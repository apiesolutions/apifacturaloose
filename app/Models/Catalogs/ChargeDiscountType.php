<?php

namespace App\Models\Catalogs;

class ChargeDiscountType extends ModelCatalog
{
    protected $table = "cat_charge_discount_types";
    public $incrementing = false;

    public function scopeWhereType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeWhereLevel($query, $level)
    {
        return $query->where('level', $level);
    }
}