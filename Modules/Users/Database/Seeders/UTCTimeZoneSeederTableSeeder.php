<?php

namespace Modules\Users\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Users\Entities\UTCTime;

class UTCTimeZoneSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('utc_time_offsets')->truncate();

        $timezones = array(
            'Pacific/Midway'       => "(UTC-11:00) Midway Island",
            'US/Samoa'             => "(UTC-11:00) Samoa",
            'US/Hawaii'            => "(UTC-10:00) Hawaii",
            'US/Alaska'            => "(UTC-09:00) Alaska",
            'US/Pacific'           => "(UTC-08:00) Pacific Time (US &amp; Canada)",
            'America/Tijuana'      => "(UTC-08:00) Tijuana",
            'US/Arizona'           => "(UTC-07:00) Arizona",
            'US/Mountain'          => "(UTC-07:00) Mountain Time (US &amp; Canada)",
            'America/Chihuahua'    => "(UTC-07:00) Chihuahua",
            'America/Mazatlan'     => "(UTC-07:00) Mazatlan",
            'America/Mexico_City'  => "(UTC-06:00) Mexico City",
            'America/Monterrey'    => "(UTC-06:00) Monterrey",
            'Canada/Saskatchewan'  => "(UTC-06:00) Saskatchewan",
            'US/Central'           => "(UTC-06:00) Central Time (US &amp; Canada)",
            'US/Eastern'           => "(UTC-05:00) Eastern Time (US &amp; Canada)",
            'US/East-Indiana'      => "(UTC-05:00) Indiana (East)",
            'America/Bogota'       => "(UTC-05:00) Bogota",
            'America/Lima'         => "(UTC-05:00) Lima",
            'America/Caracas'      => "(UTC-04:30) Caracas",
            'Canada/Atlantic'      => "(UTC-04:00) Atlantic Time (Canada)",
            'America/La_Paz'       => "(UTC-04:00) La Paz",
            'America/Santiago'     => "(UTC-04:00) Santiago",
            'Canada/Newfoundland'  => "(UTC-03:30) Newfoundland",
            'America/Buenos_Aires' => "(UTC-03:00) Buenos Aires",
            'Greenland'            => "(UTC-03:00) Greenland",
            'Atlantic/Stanley'     => "(UTC-02:00) Stanley",
            'Atlantic/Azores'      => "(UTC-01:00) Azores",
            'Atlantic/Cape_Verde'  => "(UTC-01:00) Cape Verde Is.",
            'Africa/Casablanca'    => "(UTC) Casablanca",
            'Europe/Dublin'        => "(UTC) Dublin",
            'Europe/Lisbon'        => "(UTC) Lisbon",
            'Europe/London'        => "(UTC) London",
            'Africa/Monrovia'      => "(UTC) Monrovia",
            'Europe/Amsterdam'     => "(UTC+01:00) Amsterdam",
            'Europe/Belgrade'      => "(UTC+01:00) Belgrade",
            'Europe/Berlin'        => "(UTC+01:00) Berlin",
            'Europe/Bratislava'    => "(UTC+01:00) Bratislava",
            'Europe/Brussels'      => "(UTC+01:00) Brussels",
            'Europe/Budapest'      => "(UTC+01:00) Budapest",
            'Europe/Copenhagen'    => "(UTC+01:00) Copenhagen",
            'Europe/Ljubljana'     => "(UTC+01:00) Ljubljana",
            'Europe/Madrid'        => "(UTC+01:00) Madrid",
            'Europe/Paris'         => "(UTC+01:00) Paris",
            'Europe/Prague'        => "(UTC+01:00) Prague",
            'Europe/Rome'          => "(UTC+01:00) Rome",
            'Europe/Sarajevo'      => "(UTC+01:00) Sarajevo",
            'Europe/Skopje'        => "(UTC+01:00) Skopje",
            'Europe/Stockholm'     => "(UTC+01:00) Stockholm",
            'Europe/Vienna'        => "(UTC+01:00) Vienna",
            'Europe/Warsaw'        => "(UTC+01:00) Warsaw",
            'Europe/Zagreb'        => "(UTC+01:00) Zagreb",
            'Europe/Athens'        => "(UTC+02:00) Athens",
            'Europe/Bucharest'     => "(UTC+02:00) Bucharest",
            'Africa/Cairo'         => "(UTC+02:00) Cairo",
            'Africa/Harare'        => "(UTC+02:00) Harare",
            'Europe/Helsinki'      => "(UTC+02:00) Helsinki",
            'Europe/Istanbul'      => "(UTC+02:00) Istanbul",
            'Asia/Jerusalem'       => "(UTC+02:00) Jerusalem",
            'Europe/Kiev'          => "(UTC+02:00) Kyiv",
            'Europe/Minsk'         => "(UTC+02:00) Minsk",
            'Europe/Riga'          => "(UTC+02:00) Riga",
            'Europe/Sofia'         => "(UTC+02:00) Sofia",
            'Europe/Tallinn'       => "(UTC+02:00) Tallinn",
            'Europe/Vilnius'       => "(UTC+02:00) Vilnius",
            'Asia/Baghdad'         => "(UTC+03:00) Baghdad",
            'Asia/Kuwait'          => "(UTC+03:00) Kuwait",
            'Africa/Nairobi'       => "(UTC+03:00) Nairobi",
            'Asia/Riyadh'          => "(UTC+03:00) Riyadh",
            'Europe/Moscow'        => "(UTC+03:00) Moscow",
            'Asia/Tehran'          => "(UTC+03:30) Tehran",
            'Asia/Baku'            => "(UTC+04:00) Baku",
            'Europe/Volgograd'     => "(UTC+04:00) Volgograd",
            'Asia/Muscat'          => "(UTC+04:00) Muscat",
            'Asia/Tbilisi'         => "(UTC+04:00) Tbilisi",
            'Asia/Yerevan'         => "(UTC+04:00) Yerevan",
            'Asia/Kabul'           => "(UTC+04:30) Kabul",
            'Asia/Karachi'         => "(UTC+05:00) Karachi",
            'Asia/Tashkent'        => "(UTC+05:00) Tashkent",
            'Asia/Kolkata'         => "(UTC+05:30) Kolkata",
            'Asia/Kathmandu'       => "(UTC+05:45) Kathmandu",
            'Asia/Yekaterinburg'   => "(UTC+06:00) Ekaterinburg",
            'Asia/Almaty'          => "(UTC+06:00) Almaty",
            'Asia/Dhaka'           => "(UTC+06:00) Dhaka",
            'Asia/Novosibirsk'     => "(UTC+07:00) Novosibirsk",
            'Asia/Bangkok'         => "(UTC+07:00) Bangkok",
            'Asia/Bangkok'         => "(UTC+07:00) VietNam",
            'Asia/Jakarta'         => "(UTC+07:00) Jakarta",
            'Asia/Krasnoyarsk'     => "(UTC+08:00) Krasnoyarsk",
            'Asia/Chongqing'       => "(UTC+08:00) Chongqing",
            'Asia/Hong_Kong'       => "(UTC+08:00) Hong Kong",
            'Asia/Kuala_Lumpur'    => "(UTC+08:00) Kuala Lumpur",
            'Australia/Perth'      => "(UTC+08:00) Perth",
            'Asia/Singapore'       => "(UTC+08:00) Singapore",
            'Asia/Taipei'          => "(UTC+08:00) Taipei",
            'Asia/Ulaanbaatar'     => "(UTC+08:00) Ulaan Bataar",
            'Asia/Urumqi'          => "(UTC+08:00) Urumqi",
            'Asia/Irkutsk'         => "(UTC+09:00) Irkutsk",
            'Asia/Seoul'           => "(UTC+09:00) Seoul",
            'Asia/Tokyo'           => "(UTC+09:00) Tokyo",
            'Australia/Adelaide'   => "(UTC+09:30) Adelaide",
            'Australia/Darwin'     => "(UTC+09:30) Darwin",
            'Asia/Yakutsk'         => "(UTC+10:00) Yakutsk",
            'Australia/Brisbane'   => "(UTC+10:00) Brisbane",
            'Australia/Canberra'   => "(UTC+10:00) Canberra",
            'Pacific/Guam'         => "(UTC+10:00) Guam",
            'Australia/Hobart'     => "(UTC+10:00) Hobart",
            'Australia/Melbourne'  => "(UTC+10:00) Melbourne",
            'Pacific/Port_Moresby' => "(UTC+10:00) Port Moresby",
            'Australia/Sydney'     => "(UTC+10:00) Sydney",
            'Asia/Vladivostok'     => "(UTC+11:00) Vladivostok",
            'Asia/Magadan'         => "(UTC+12:00) Magadan",
            'Pacific/Auckland'     => "(UTC+12:00) Auckland",
            'Pacific/Fiji'         => "(UTC+12:00) Fiji",
        );
        foreach ($timezones as $key => $val) {
            $data = [
                'name' => $val,
                'value' => $key
            ];
            UTCTime::create($data);
        }

    }
}
