<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checklist extends Model
{
    use HasFactory;

    protected $table = 'checklists';

    protected $fillable = [
        'title',
        // 'audit_id',
        'status',
        'created_by'
    ];

    // public function audit()
    // {
    //     return $this->belongsTo(Audit::class, 'audit_id');
    // }

}
