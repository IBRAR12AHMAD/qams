<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShowSchedule extends Model
{
    use HasFactory;

    protected $table = 'show_schedule';

    protected $fillable = [
        'row_ordernumber',
        'checklist_id',
        'parameter',
        'submit_type',
        'created_by',
        'schedule_aditor_id',
        'schedule_id',
        'asing_to',
        'asing_by',
        'asingby_remarks',
        'schedule_status',
        'partial_compliant',
        'compliant',
        'non_compliant',
        'responded_remarks',
        'responded_submit_type'

    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'employee_id');
    }


}
