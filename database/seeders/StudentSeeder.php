<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Student;
use App\Models\ParentModel;

class StudentSeeder extends Seeder
{
    public function run()
    {
        $oldParents = DB::connection('mysqlold')->table('parents')->get();
        $oldStudents = DB::connection('mysqlold')->table('student')
            ->where('active', 1)
            ->where('classesID', '>', 0)  // Only valid class IDs
            ->where('sectionID', '>', 0)   // Only valid section IDs
            ->get();

        // Build a mapping of old parentID to new parent_id by matching on unique fields (e.g., cnic, phone, email, name)
        $parentMap = [];
        foreach ($oldParents as $oldParent) {
            // Find the parent user in the new users table by matching the old parent data
            $parentUser = User::where('user_type', 'parent')
                ->where('name', $oldParent->name)
                ->where('phone', $oldParent->phone)
                ->first();
            
            if ($parentUser) {
                // Get the parent record associated with this user
                $parent = ParentModel::where('user_id', $parentUser->id)->first();
                if ($parent) {
                    $parentMap[$oldParent->parentsID] = $parent->id;
                }
            }
        }

        $dummyCounter = 1;
        $existingEmails = User::pluck('email')->toArray();

        foreach ($oldStudents as $old) {
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
                'country'           => $old->country ?? 'Pakistan',
                'state'             => $old->state ?? null,
                'city'              => null, 
                'blood_group'       => $old->bloodgroup,
                'avatar'            => $old->photo ?? null,
                'user_type'         => 'student',
                'registration_no'   => $old->registerNO,
                'transport_status'  => $old->transport_sts ?? 0,
                'transport_id'      => $old->transport_id,
                'is_active'         => $old->active,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
                'created_by'        => 'Super Admin User',
                'updated_by'        => null,
            ]);

            // Map old parentID to new parent_id
            $parent_id = $old->parentID && isset($parentMap[$old->parentID]) ? $parentMap[$old->parentID] : null;

            // Skip student if parent_id cannot be resolved (i.e., parent not found/migrated)
            if (is_null($parent_id)) {
                echo "Skipping student '{$old->name}' (Old ID: {$old->studentID}) due to unresolvable parentID: {$old->parentID}\n";
                continue; // Skip this student
            }

            // Insert into `students` table
            Student::create([
                'user_id'           => $user->id,
                'parent_id'         => $parent_id,
                'admission_date'    => $old->admission_date,
                'class_id'          => $old->classesID,
                'section_id'        => $old->sectionID,
                'roll_no'           => $old->roll,
                'library_status'    => $old->library ?? 0,
                'hostel_status'     => $old->hostel ?? 0,
                'created_by'        => 'Super Admin User',
                'updated_by'        => null,
            ]);
        }
    }
}
