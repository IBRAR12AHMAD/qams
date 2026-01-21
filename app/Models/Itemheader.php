<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Itemheader extends Model
{
    use HasFactory;

    protected $table = 'itemheaders';

    protected $fillable = [
        'title',
        'status',
        'order_number',
        'created_by'
    ];
}
