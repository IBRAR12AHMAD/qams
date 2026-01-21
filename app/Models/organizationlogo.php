<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class organizationlogo extends Model
{
    use HasFactory;

    protected $table = 'organizationlogos';

    protected $fillable = [
        'org_logo',
    ];
}
