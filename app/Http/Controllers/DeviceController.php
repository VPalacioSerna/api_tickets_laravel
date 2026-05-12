<?php

namespace App\Http\Controllers;

use App\Services\DeviceService;
use App\Http\Requests\AssignDeviceRequest;
use Illuminate\Http\JsonResponse;

class DeviceController extends Controller
{
    public function __construct(protected DeviceService $deviceService) {}

    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $this->deviceService->getAll(),
        ]);
    }

    public function assign(AssignDeviceRequest $request): JsonResponse
    {
        $assignment = $this->deviceService->assign($request->validated());

        return response()->json([
            'success' => true,
            'data'    => $assignment,
        ], 201);
    }
}
