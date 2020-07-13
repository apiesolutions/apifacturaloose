<?php
namespace App\Models\Catalogs;

use Illuminate\Database\Eloquent\Model;

class ModelCatalog extends Model
{
    public function scopeWhereActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeOrderByDescription($query)
    {
        return $query->orderBy('description');
    }
}