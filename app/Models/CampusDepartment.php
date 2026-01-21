<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampusDepartment extends Model
{
    use HasFactory;

    protected $table = 'campus_departments';
    protected $fillable = [
        'campus_id',
        'department_id',
        'program_id',
        'status',
        'created_by'
    ];

    public function campus()
    {
        return $this->belongsTo(Campus::class, 'campus_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

}
