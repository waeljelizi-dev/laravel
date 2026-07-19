<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $revenueData = [1200, 1500, 1800, 2200, 2600];
        $userData = [10, 25, 40, 30, 50];
        $ordersData = [5, 12, 18, 10,25];
        return view('dashboard.index', compact(
            'revenueData',
            'userData',
            'ordersData'
        ));
    }

    
}
