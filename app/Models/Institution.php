<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    protected $fillable = [
        'name',
        'type',
    ];

    public function major()
    {
        return $this->hasMany(Major::class);
    }
}
