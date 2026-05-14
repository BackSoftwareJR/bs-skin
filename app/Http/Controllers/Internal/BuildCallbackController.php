<?php

declare(strict_types=1);

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BuildCallbackController extends Controller
{
    /**
     * Callback per compatibilità con piano hosting.
     * Nel monolite non serve rebuild, ma manteniamo per futura compatibilità.
     */
    public function __invoke(Request $request): JsonResponse
    {
        return response()->json(['status' => 'ok'], 204);
    }
}