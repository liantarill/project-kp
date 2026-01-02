<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'date',
        'check_in',
        'check_out',
        'user_id',
        'status',
        'note',
        'photo',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'date' => 'datetime',
        'check_in' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
