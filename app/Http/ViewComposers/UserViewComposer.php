<?php

namespace App\Http\ViewComposers;

class UserViewComposer
{
    public function compose($view)
    {
        $view->vc_user = auth()->user();
    }
}