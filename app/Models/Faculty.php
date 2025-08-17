<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Faculty extends Model
{
    protected $table = 'faculty';
    protected $primaryKey = 'Faculty_ID';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'rfid_tag',
        'FirstName',
        'LastName',
        'Email',
        'Position',
        'Gender',
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'Faculty_ID', 'Faculty_ID');
    }

    public function classSchedules(): HasMany
    {
        return $this->hasMany(ClassSchedule::class, 'faculty_ID', 'Faculty_ID');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'Faculty_ID', 'Faculty_ID');
    }
}
