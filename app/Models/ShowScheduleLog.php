<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShowScheduleLog extends Model
{
    protected $table = 'showschedule_log';

    protected $fillable = [
        'schedule_id',
        'schedule_created',
        'schedule_aditor_id',
        'showschedule_id',
        'row_ordernumber',
        'checklist_id',
        'parameter',
        'submit_type',
        'asing_by',
        'asingby_remarks',
        'compliant',
        'partial_compliant',
        'non_compliant',
        'asing_to',
        'schedule_status',
        'responded_submit_type',
        'responded_remarks',
        'created_by'
    ];
}
