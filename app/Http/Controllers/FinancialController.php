<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FinancialController extends Controller
{
    public function index()
    {
        return view('pages.app.financial.index');
    }

    public function detail($record_id)
    {
        return view('pages.app.financial.detail', compact('record_id'));
    }

    public function statistics()
    {
        return view('pages.app.financial.statistics');
    }
}