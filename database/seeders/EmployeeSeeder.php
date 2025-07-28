<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Employee;
use App\Models\Designation;

class EmployeeSeeder extends Seeder
{
    public function run()
    {
        $oldEmployees = DB::connection('mysqlold')->table('user')->where('active', 1)->get();

        $dummyCounter = 1;
        $existingEmails = User::pluck('email')->toArray();

        foreach ($oldEmployees as $old) {
            $email = trim($old->email);
            if (empty($email) || in_array($email, $existingEmails)) {
                // Generate a unique dummy email
                do {
                    $email = 'dummy' . $dummyCounter . '@noemail.local';
                    $dummyCounter++;
                } while (in_array($email, $existingEmails));
            }
            $existingEmails[] = $email;

            // Insert into `users` table
            $user = User::create([
                'name'              => $old->name,
                'email'             => $email,
                'username'          => $old->username,
                'password'          => Hash::make('BGS12345@'),
                'dob'               => $old->dob,
                'gender'            => $old->sex,
                'religion'          => $old->religion,
                'phone'             => $old->phone,
                'address'           => $old->address,
                'country'           => 'Pakistan', // Not available in old data
                'state'             => null, // Not available in old data
                'city'              => 'Bagh',
                'blood_group'       => $old->bloodgroup,
                'avatar'            => $old->photo ?? null,
                'user_type'         => 'employee',
                'transport_status'  => $old->transport_sts ?? 0,
                'transport_id'      => $old->transport_id,
                'is_active'         => $old->active,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
                'created_by'        => 'Super Admin User',
                'updated_by'        => null,
            ]);

            // Insert into `employees` table (only columns that exist)
            Employee::create([
                'user_id'           => $user->id,
                'designation_id'    => 42,
                'cnic'              => $old->cnic,
                'joining_date'      => $old->jod,
                'basic_salary'      => $old->basic_sal ?? null,
                'created_by'        => 'Super Admin User',
                'updated_by'        => null,
            ]);
        }
    }
}
