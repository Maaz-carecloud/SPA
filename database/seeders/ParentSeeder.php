<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Models\ParentModel;

class ParentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $oldParents = DB::connection('mysqlold')->table('parents')->where('active', 1)->get();

        $dummyCounter = 1;
        $existingEmails = User::pluck('email')->toArray();

        foreach ($oldParents as $old) {
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
                'phone'             => $old->phone,
                'address'           => $old->address,
                'user_type'         => 'parent',
                'is_active'         => $old->active,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
                'created_by'        => 'Super Admin User',
                'updated_by'        =>  null,
            ]);

            // Insert into `parents` table
            ParentModel::create([
                'user_id'           => $user->id,
                'cnic'              => $old->cnic,
                'father_profession' => $old->father_profession,
                'mother_name'       => $old->mother_name,
                'mother_contact'    => $old->contact1,
                'mother_profession' => $old->mother_profession,
                'ntn_no'            => $old->ntn_no,
                'created_by'        => 'Super Admin User',
                'updated_by'        => null,
            ]);
        }
    }
}
