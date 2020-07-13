<?php

namespace App\Http\ViewComposers;

use App\Models\Company;

class CompanyViewComposer
{
    public function compose($view)
    {
        $view->vc_company = Company::active();
    }
}