<?php

namespace App\CoreFacturalo\Requests\Api\Transform\Common;

use App\CoreFacturalo\Requests\Api\Transform\Functions;
use App\Models\Catalogs\Country;
use App\Models\Catalogs\Department;
use App\Models\Catalogs\District;
use App\Models\Catalogs\Province;

class EstablishmentTransform
{
    public static function transform($inputs)
    {
        $location_id = Functions::valueKeyInArray($inputs, 'ubigeo', '150101');
        $country_id = Functions::valueKeyInArray($inputs, 'codigo_pais', 'PE');
        $department_id = substr($location_id, 0, 2);
        $province_id = substr($location_id, 0, 4);
        $district_id = $location_id;

        return [
            'country_id' => $country_id,
            'country' => [
                'id' => $country_id,
                'description' => Country::find($country_id)->description,
            ],
            'department_id' => $department_id,
            'department' => [
                'id' => $department_id,
                'description' => Department::find($department_id)->description,
            ],
            'province_id' => $province_id,
            'province' => [
                'id' => $province_id,
                'description' => Province::find($province_id)->description,
            ],
            'district_id' => $district_id,
            'district' => [
                'id' => $district_id,
                'description' => District::find($district_id)->description,
            ],
            'urbanization' => Functions::valueKeyInArray($inputs, 'urbanizacion'),
            'address' => Functions::valueKeyInArray($inputs, 'direccion'),
            'email' => Functions::valueKeyInArray($inputs, 'correo_electronico'),
            'telephone' => Functions::valueKeyInArray($inputs, 'telefono'),
            'code' => $inputs['codigo_del_domicilio_fiscal'],
        ];
    }
}