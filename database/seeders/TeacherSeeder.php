<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Designation;

class TeacherSeeder extends Seeder
{
    public function run()
    {
        $oldTeachers = DB::connection('mysqlold')->table('teacher')->where('active', 1)->get();

        foreach ($oldTeachers as $old) {
            // Insert into `users` table
            $user = User::create([
                'name'              => $old->name,
                'email'             => $old->email,
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
                'cnic'              => $old->cnic,
                'blood_group'       => $old->bloodgroup,
                'avatar'            => null,
                'user_type'         => 'teacher',
                'transport_status'  => $old->transport_sts ?? 0,
                'transport_id'      => $old->transport_id,
                'registration_no'   => $old->teacher_regno,
                'is_active'         => $old->active,
                'created_at'        => $old->create_date ?? Carbon::now(),
                'updated_at'        => $old->modify_date ?? Carbon::now(),
                'created_by' => 'Super Admin User',
                'updated_by'        => null,
            ]);

            // Map designation name to designation_id
            $designationId = null;
            if (!empty($old->designation)) {
                $designation = Designation::where('name', $old->designation)->first();
                if ($designation) {
                    $designationId = $designation->id;
                }
            }

            // Insert into `teachers` table (only columns that exist)
            Teacher::create([
                'user_id'           => $user->id,
                'designation_id'    => $designationId,
                'joining_date'      => $old->jod,
                'qualification'     => $old->cf_qualification ?? null,
                'basic_salary'      => $old->basic_sal ?? null,
                'created_by' => 'Super Admin User',
                'updated_by'        => null,
            ]);
        }
    }
}
