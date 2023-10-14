<?php

namespace Database\Seeders;

use App\Helpers\AppHelper;
use App\Models\SuperAdmin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminInfo = array();
        $adminInfo['full_name'] = 'Chamud Sachintha';
        $adminInfo['email'] = "abc123@gmail.com";
        $adminInfo['mobile_number'] = "11111111";
        $adminInfo['password'] = Hash::make(123);
        $adminInfo['flag'] = "SA";
        $adminInfo['create_time'] = (new AppHelper())->get_date_and_time();

        (new SuperAdmin())->create($adminInfo);
    }
}
