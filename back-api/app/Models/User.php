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

    public function signatures()
    {
        return $this->hasMany(Signatures::class, 'user_id');
    }
}
