<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasUuids, Notifiable, SoftDeletes;

    protected $keyType = 'string';

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'institution_id',
        'major',
        'level',
        'status',
        'department_id',
        'acceptance_proof',
        'start_date',
        'end_date',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function canAccessFilament(): bool
    {
        return $this->role === 'admin';
    }

    public static function activeParticipant()
    {
        return self::where('role', 'participant')
            ->where('status', 'active')
            ->count();
    }
}
