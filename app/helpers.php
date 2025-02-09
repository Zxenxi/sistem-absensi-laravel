<?php
use Illuminate\Support\Facades\Route;

use Carbon\Carbon;
use App\Models\PiketSchedule;
use Illuminate\Support\Facades\Auth;

if (! function_exists('isOnDuty')) {
    function isOnDuty() {
        if (Auth::check() && Auth::user()->role === 'guru') {
            $now = Carbon::now('Asia/Jakarta');
            $today = $now->toDateString();
            $piket = PiketSchedule::where('guru_id', Auth::user()->id)
                        ->whereDate('schedule_date', $today)
                        ->first();
            if ($piket) {
                if ($piket->start_time && $piket->end_time) {
                    $start = Carbon::parse($piket->start_time, 'Asia/Jakarta');
                    $end   = Carbon::parse($piket->end_time, 'Asia/Jakarta');
                    return $now->between($start, $end);
                }
                return true; // Jika tidak ada waktu, asumsikan bertugas penuh
            }
        }
        return false;
    }
}


if (!function_exists('isActive')) {
    function isActive($routeName)
    {
        return request()->is(trim($routeName, '/')) ? 'bg-purple-600 text-white' : '';
    }
}
// function calculateDistance($lat1, $lon1, $lat2, $lon2) {
//     $earthRadius = 6371000; // dalam meter
//     $latFrom = deg2rad($lat1);
//     $lonFrom = deg2rad($lon1);
//     $latTo = deg2rad($lat2);
//     $lonTo = deg2rad($lon2);
//     $latDelta = $latTo - $latFrom;
//     $lonDelta = $lonTo - $lonFrom;
//     $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
//               cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
//     return $angle * $earthRadius;
// }

// rumus vincenty

function calculateDistance($lat1, $lon1, $lat2, $lon2) {
    // Konstanta untuk model elipsoid WGS-84
    $a = 6378137; // Radius equator dalam meter
    $b = 6356752.3142; // Radius polar dalam meter
    $f = 1 / 298.257223563; // Flattening

    // Konversi koordinat dari derajat ke radian
    $lat1 = deg2rad($lat1);
    $lon1 = deg2rad($lon1);
    $lat2 = deg2rad($lat2);
    $lon2 = deg2rad($lon2);

    // Diferensi antara koordinat longitud
    $L = $lon2 - $lon1;

    $U1 = atan((1 - $f) * tan($lat1));
    $U2 = atan((1 - $f) * tan($lat2));

    $sinU1 = sin($U1);
    $cosU1 = cos($U1);
    $sinU2 = sin($U2);
    $cosU2 = cos($U2);

    $lambda = $L;
    $lambdaP = 2 * M_PI;

    $iterLimit = 20;

    while (abs($lambda - $lambdaP) > 1e-12 && --$iterLimit > 0) {
        $sinLambda = sin($lambda);
        $cosLambda = cos($lambda);
        $sinSigma = sqrt(($cosU2 * $sinLambda) ** 2 +
                           ($cosU1 * $sinU2 - $sinU1 * $cosU2 * $cosLambda) ** 2);
        $cosSigma = $sinU1 * $sinU2 + $cosU1 * $cosU2 * $cosLambda;
        $sigma = atan2($sinSigma, $cosSigma);
        $sinAlpha = ($cosU1 * $cosU2 * $sinLambda) / $sinSigma;
        $cosSqAlpha = 1 - $sinAlpha ** 2;
        $cos2SigmaM = $cosSigma - 2 * $sinU1 * $sinU2 / $cosSqAlpha;
        $C = ($f / 16) * $cosSqAlpha * (4 + $f * (4 - 3 * $cosSqAlpha));
        $lambdaP = $lambda;
        $lambda = $L + (1 - $C) * $f * $sinAlpha *
                   ($sigma + $C * $sinSigma * ($cos2SigmaM + $C * $cosSigma * (-1 + 2 * $cos2SigmaM ** 2)));
    }

    if ($iterLimit == 0) {
        return null; // Konvergensi tidak tercapai
    }

    $uSq = $cosSqAlpha * (($a ** 2 - $b ** 2) / ($b ** 2));
    $A = 1 + ($uSq / 16384) * (4096 + $uSq * (-768 + $uSq * (320 - 175 * $uSq)));
    $B = ($uSq / 1024) * (256 + $uSq * (-128 + $uSq * (74 - 47 * $uSq)));
    $cos2SigmaM = cos(2 * $U1 + $sigma);
    $sinSigma = sin($sigma);
    $cosSigma = cos($sigma);
    $deltaSigma = $B * $sinSigma * ($cos2SigmaM + $B / 4 *
                                      ($cosSigma * (-1 + 2 * $cos2SigmaM ** 2) - $B / 6 * $cos2SigmaM *
                                      (-3 + 4 * $sinSigma ** 2) * (-3 + 4 * $cos2SigmaM ** 2)));

    $s = $b * $A * ($sigma - $deltaSigma);

    return $s; // Jarak dalam meter
}