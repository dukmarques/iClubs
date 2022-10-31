<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $hidden = [
        'deleted_at',
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'signature_id');
    }
}
