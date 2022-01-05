<?php

namespace Database\Seeders;

use App\Models\DetailUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DetailUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $detail_users = [
            [
                'users_id'              => 1,
                'photo'                 => '',
                'role'                  => 'Website Developer',
                'contact_number'        => '',
                'biography'        => '',
                'created_at'            => date('Y-m-d h:i:s'),
                'updated_at'            => date('Y-m-d h:i:s')
            ]
        ];

        DetailUser::insert($detail_users);
    }
}
