<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\RestaurantAPIController;

class RestaurantViewController extends Controller
{
    private $api;

    public function __construct()
    {
        $this->api = new RestaurantAPIController();
    }

    public function index(Request $request)
    {
        $restaurants = $this->api->paginatedIndex($request)->withQueryString();

        return view('restaurants.index',compact('restaurants'));
    }
}
