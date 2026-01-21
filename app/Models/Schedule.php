<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $table = 'schedules';

    protected $fillable = [
        'title',
        'start_date',
        'program_id',
        'audit_id',
        'status',
        'checklist_id',
        'aditor_id',
        'campus_id',
        'department_id',
        'created_by'
    ];

    // 🔑 Relationship: aditor_id → users.employee_id
    public function aditor()
    {
        return $this->belongsTo(User::class, 'aditor_id', 'employee_id');
    }

}
