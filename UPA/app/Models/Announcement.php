<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $table = 'announcement';

    protected $fillable = [
        'title',
        'content',
        'type',
        'target_audience',
        'event_date',
        'pickup_certificate',
        'created_by',
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'pickup_certificate' => 'datetime',
    ];
}
