<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{
    public function getProvinces(Request $request)
    {
        $query = $request->query('query', '');
        $provinces = DB::table('provinces')
            ->when($query, function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%');
            })
            ->orderBy('name')
            ->get();
        return response()->json($provinces);
    }

    public function getWardsByProvince($province_code, Request $request)
    {
        $query = $request->query('query', '');
        $wards = DB::table('wards')
            ->where('province_code', $province_code)
            ->when($query, function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%');
            })
            ->orderBy('name')
            ->get();
        return response()->json($wards);
    }
}
