<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class GuruSeeder extends Seeder
{
    public function run(): void
    {
        $teacherNames = [
            "SETYAWAN ARI RESPATI, S.Pd.",
            "KUKUH YOGA GANJAR SUTARWAN, S.Pd.",
            "TRI WINARS",
            "DWI KRISTIANTO, S.Pd.",
            "PRABANTORO SAPUTRO, S.Pd.",
            "SRI YANARI",
            "ENDANG WAHYUNINGSIH, Apt., M. Kes.",
            "SULISTYOWATI, S.PAK",
            "MAYDA ADIYANTI, S.Pd.",
            "LISA PRASTYANTI, S.S.",
            "DYAH SIWI R., S.E.",
            "CAHYANING RATRI, S.Pd.",
            "WIDYANTI, S.Pd.",
            "Apt. AGUSRI ARI MURTI KRISTYANTI, M.Farm.",
            "FIRMANSYAH AL FATONI",
            "Apt. FRANSISCA INDAH PRATIWI, M.Farm.",
            "DRA. SUHARTATI",
            "CHRISTIANA YUNI WULANDARI, S.Pd.",
            "PUJI WALUYO, S.Kom.",
            "DRS. DIDIK HADI PRATIYO",
            "CATARINA SUGIHARNI, S.Pd.",
            "SRI WENING ARIANI, S.Pd.",
            "WATINI",
            "JONI DOSO PRIYANTO, S.SIP.",
            "ENI MURTATI, S.E.",
            "MARIA SUCI DEWI LESTARI, S.E., S.Pd.",
            "HERIYANTO",
            "EKO PRASTOWO",
            "PUROMO",
        ];

        foreach ($teacherNames as $name) {
            // Buat email dari nama: hilangkan spasi, koma, dan titik, lalu lowercase
            $email = strtolower(preg_replace('/[\s,\.]+/', '', $name)) . '@example.com';
            User::create([
                'name'              => $name,
                'email'             => $email,
                'role'              => 'guru',
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
            ]);
        }
    }
}