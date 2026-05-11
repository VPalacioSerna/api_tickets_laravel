<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceAssignment extends Model
{
    use HasFactory;

    protected $fillable = ['device_id', 'user_id', 'assigned_at'];

    public function device(): BelongsTo { return $this->belongsTo(Device::class); }
    public function user(): BelongsTo  { return $this->belongsTo(User::class); }
}
