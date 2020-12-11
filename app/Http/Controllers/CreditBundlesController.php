<?php

namespace App\Http\Controllers;

use App\Models\CreditBundle;
use Illuminate\Http\Request;

class CreditBundlesController extends Controller
{
    public function index(Request $request)
    {
        return CreditBundle::where('active', true)->get();
    }
}
