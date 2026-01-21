<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkitem extends Model
{
    use HasFactory;

    protected $table = 'checkitems';

    protected $fillable = [
        // 'title',
        'parameter',
        'header_id',
        'order_number',
        'checklist_id',
        'select_items',
        'row_ordernumber',
        'status',
        'created_by'
    ];

    protected $casts = [
        'checklist_id' => 'array',
    ];

        // Define relationship
    public function checklist()
    {
        return $this->belongsTo(Checklist::class, 'checklist_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'checklist_id', 'checklist_id');
    }


}
