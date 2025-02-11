<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_image',
        'event_title',
        'event_type',
        'date',
        'time',
        'location',
        'description',
    ];
}
