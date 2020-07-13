<?php

namespace App\Models\Catalogs;

class PaymentMethodType extends ModelCatalog
{
    protected $table = "cat_payment_method_types";
    public $incrementing = false;
}