<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'employee_id',
        'employee_old_id',
        'name',
        'sap_id',
        'specilaity',
        'sub_specilaity',
        'semester',
        'session_id',
        'password',
        'strategic_unit',
        'designation',
        'email',
        'functionally_reports_to',
        'functional_head_name',
        'administratively_reports_to',
        'admin_head_name',
        'gender',
        'address',
        'phone_number',
        'role_id',
        'status',
        'su_head',
        'unique_id',
        'campus_id',
        'department_id',
        'org_o',
        'profile_img',
        'authenticated_id',
        'created_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // public function studentsession()
    // {
    //     return $this->belongsTo(StudentSession::class, 'session_id');
    // }

    public function role(){
        return $this->belongsTo(Role::class,'role_id','id');
    }
    public function hasRole($roleName)
    {
        return $this->roles->where('guard_name', $roleName)->count() > 0;
    }

}
