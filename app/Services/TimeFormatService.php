<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class TimeFormatService
{
    public function formatDate($datetimeString = null)
    {
        Carbon::setLocale('id');
        // Untuk sementara, kita hardcode timezone berdasarkan asumsi bahwa
        // aplikasi ini untuk Indonesia, jadi semua order menggunakan WIB
        if (!$datetimeString) {
            // Konversi ke WIB (Asia/Jakarta) dan paksa tampil sebagai WIB
            $carbon = $datetimeString->setTimezone('Asia/Jakarta');
            return $carbon->isoFormat('dddd, D MMMM YYYY, HH:mm') . ' WIB';
        } else {
            $carbon = Carbon::parse($datetimeString);
            
            // Jika ada timezone info di string, deteksi
            if (is_string($datetimeString) && preg_match('/([+-]\d{2})(\d{2})?$/', $datetimeString, $matches)) {
                $offsetHour = (int)$matches[1];
                $timezoneLabel = match ($offsetHour) {
                    7 => 'WIB',
                    8 => 'WITA', 
                    9 => 'WIT',
                    default => sprintf('UTC%+d', $offsetHour),
                };
                return $carbon->isoFormat('dddd, D MMMM YYYY, HH:mm') . " $timezoneLabel";
            } else {
                // Default ke WIB
                return $carbon->setTimezone('Asia/Jakarta')->isoFormat('dddd, D MMMM YYYY, HH:mm') . ' WIB';
            }
        }
    }

    // Accessor untuk formatted order date
    public function getFormattedOrderDateAttribute()
    {
        return $this->formatDate();
    }

}
