<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Config;
use App\Models\Vote;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function showHomepage(Request $request){
        // FETCH DATA
        $datas = Candidate::with('event')->with('votes')->get();

        $datasBEM = $datas->filter(function ($data) {
            return $data['event']['type'] == 'BEM';
        });

        $datasDPM = $datas->filter(function ($data) {
            return $data['event']['type'] == "DPM";
        });

        $datasHIMASI = $datas->filter(function ($data) {
            return $data['event']['id_prodi'] == "0101";
        });

        $datasHIMAIF = $datas->filter(function ($data) {
            return $data['event']['id_prodi'] == "0102";
        });

        $datasHIMASIA = $datas->filter(function ($data) {
            return $data['event']['id_prodi'] == "0103";
        });

        $datasHIMADKV = $datas->filter(function ($data) {
            return $data['event']['id_prodi'] == "0104";
        });

        $datasHIMAManajemen = $datas->filter(function ($data) {
            return $data['event']['id_prodi'] == "0201";
        });

        $datasHIMAAkuntansi = $datas->filter(function ($data) {
            return $data['event']['id_prodi'] == "0202";
        });

        $datasHIMABD = $datas->filter(function ($data) {
            return $data['event']['id_prodi'] == "0203";
        });

        $config = Config::get();

        return Inertia::render('Homepage', [
            'datas' => [
                'dpm' => $datasDPM,
                'bem' => $datasBEM,
                'hima_if' => $datasHIMAIF,
                'hima_si' => $datasHIMASI,
                'hima_sia' => $datasHIMASIA,
                'hima_dkv' => $datasHIMADKV,
                'hima_manajemen' => $datasHIMAManajemen,
                'hima_akuntansi' => $datasHIMAAkuntansi,
                'hima_bd' => $datasHIMABD,
            ],
            'config' => $config
        ]);
    }
}
