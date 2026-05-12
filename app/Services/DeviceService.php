<?php

namespace App\Services;

use App\Models\Device;
use App\Models\DeviceAssignment;
use Illuminate\Support\Collection;

class DeviceService
{
    public function getAll(): Collection
    {
        return Device::with('assignments.user')->get();
    }

    public function assign(array $data): DeviceAssignment
    {
        $device = Device::findOrFail($data['device_id']);

        if ($device->status === 'assigned') {
            throw new \Exception('El dispositivo ya está asignado.');
        }

        $device->update(['status' => 'assigned']);

        return DeviceAssignment::create([
            'device_id'   => $data['device_id'],
            'user_id'     => $data['user_id'],
            'assigned_at' => now(),
        ]);
    }
}
