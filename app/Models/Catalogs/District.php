<?php

namespace App\Models\Catalogs;

class District extends ModelCatalog
{
    public $incrementing = false;
    public $timestamps = false;

    static function idByDescription($description, $province_id)
    {
        $district = District::where('description', $description)
                            ->where('province_id', $province_id)
                            ->first();
        if ($district) {
            return $district->id;
        }
        return '150101';
    }
}