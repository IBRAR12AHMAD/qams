<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $table = 'programs';
    protected $fillable = [
        'program_name',
        'department_id',
        'status',
        'created_by'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

}
