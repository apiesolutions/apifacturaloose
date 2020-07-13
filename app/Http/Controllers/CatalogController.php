<?php
namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\SoapType;
use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyRequest;
use App\Http\Resources\CompanyResource;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index()
    {
        return view('tenant.catalogs.index');
    }
}