<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'serial_number', 'status'];

    public function assignments(): HasMany
    {
        return $this->hasMany(DeviceAssignment::class);
    }
}
