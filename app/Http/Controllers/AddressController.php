<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\City;
use App\Models\Country;
use App\Models\Region;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function showCountries()
    {
        $countries = Country::all();
        return response()->json([
            'data' => $countries,
            'status code' => http_response_code(),
        ]);
    }

    public function showCities()
    {
        $cities = City::with('countries')->get();
        return response()->json([
            'data' => $cities,
            'status code' => http_response_code(),
        ]);
    }

    public function showRegions()
    {
        $regions = Region::with('cities', 'cities.countries')->get();
        return response()->json([
            'data' => $regions,
            'status code' => http_response_code(),
        ]);
    }

    public function showAddresses()
    {
        $addresses = Address::with('regions', 'regions.cities', 'regions.cities.countries')->get();
        return response()->json([
            'data' => $addresses,
            'status code' => http_response_code(),
        ]);
    }
}
