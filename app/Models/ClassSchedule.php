<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassSchedule extends Model
{
    protected $table = 'class_schedules';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'subject',
        'section',
        'Yearlvl',
        'day_of_week',
        'start_time',
        'end_time',
        'room',
        'faculty_ID',
    ];

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class, 'faculty_ID', 'Faculty_ID');
    }
}
