<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fund extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'details',
        'start_date',
        'end_date',
        'total_funds',
        'target_funds',
        'profit',
        'image'
    ];
}
