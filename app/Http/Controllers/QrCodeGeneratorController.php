<?php

namespace App\Http\Controllers;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeGeneratorController extends Controller
{
    public function generate($productCode) {

        $route = 'http://192.168.2.104:3333/sales/create/'.$productCode;

        $qrCode = QrCode::size(300)->format('png')->generate($route);

        return response($qrCode)->header('Content-Type', 'image/png');
    }
}
