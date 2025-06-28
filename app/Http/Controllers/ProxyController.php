<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProxyController extends Controller
{
    public function getProvinces(Request $request)
    {
        $query = $request->query('query', '');
        $page = $request->query('page', 0);
        $size = $request->query('size', 30);

        $url = "https://open.oapi.vn/location/provinces?page={$page}&size={$size}&query={$query}";
        $response = Http::get($url);

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json(['error' => 'Không thể lấy dữ liệu tỉnh/thành'], 500);
    }
    public function getDistricts($provinceId, Request $request)
    {
        $page = $request->query('page', 0);
        $size = $request->query('size', 30);

        $url = "https://open.oapi.vn/location/districts/{$provinceId}?page={$page}&size={$size}";
        $response = Http::get($url);

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json(['error' => 'Không thể lấy dữ liệu quận/huyện'], 500);
    }

    public function getWards($districtId, Request $request)
    {
        $page = $request->query('page', 0);
        $size = $request->query('size', 30);

        $url = "https://open.oapi.vn/location/wards/{$districtId}?page={$page}&size={$size}";
        $response = Http::get($url);

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json(['error' => 'Không thể lấy dữ liệu phường/xã'], 500);
    }
}
