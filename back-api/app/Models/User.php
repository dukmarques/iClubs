<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    use HasApiTokens, HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email'
    ];

    protected $hidden = [
        'deleted_at',
    ];

    public function clubs()
    {
        return $this->belongsToMany(Clubs::class, 'signatures', 'user_id', 'club_id');
    }
}
