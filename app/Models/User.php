<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'user_type', 
        'dob',
        'gender',
        'religion',
        'phone',
        'address',
        'country',
        'city',
        'state',
        'avatar',
        'cnic',
        'blood_group',
        'registration_no',
        'transport_status',
        'transport_id',
        'is_active',
        'library',    
        'created_by',
        'updated_by'

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Profile Relationships
    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function parentProfile()
    {
        return $this->hasOne(ParentModel::class);
    }

    // Alias for backward compatibility
    public function parent()
    {
        return $this->parentProfile();
    }

    // Leave Management Relationships
    public function leaveRecords()
    {
        return $this->hasMany(LeaveRecord::class, 'user_id');
    }

    public function logs()
    {
        return $this->hasMany(ActivityLog::class, 'userId');
    }

    // Accessors
    public function getProfilePhotoUrlAttribute()
    {
        
        if ($this->avatar && file_exists(storage_path('app/public/' . $this->avatar))) {
            return asset('storage/' . $this->avatar);
        }
        
        // Fallback to UI Avatars service
        $name = urlencode($this->name);
        return "https://ui-avatars.com/api/?name={$name}&color=7F9CF5&background=EBF4FF";
    }

}
