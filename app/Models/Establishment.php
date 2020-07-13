<?php

namespace App\Models;

use App\Models\Catalogs\Country;
use App\Models\Catalogs\Department;
use App\Models\Catalogs\District;
use App\Models\Catalogs\Province;
use Illuminate\Database\Eloquent\Model;

class Establishment extends Model
{
    protected $with = ['country', 'department', 'province', 'district'];
    protected $fillable = [
        'description',
        'country_id',
        'department_id',
        'province_id',
        'district_id',
        'address',
        'email',
        'telephone',
        'code',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function getAddressFullAttribute()
    {
        $address = ($this->address != '-')? $this->address.' ,' : '';
        return "{$address} {$this->department->description} - {$this->province->description} - {$this->district->description}";
    }
}