<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnalyzePerformanceRequest;
use App\Services\LightHouseService;
use Illuminate\Http\Request;

class LightHouseController extends Controller
{
    private $lighthouse;

    public function __construct(LightHouseService $lighthouse)
    {
        $this->lighthouse = $lighthouse;
    }


    public function trackPerformance(AnalyzePerformanceRequest $request)
    {
        $result = $this->lighthouse->trackPerformance($request->all());

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], 400);
        }

        return response()->json(['data' => $result],201);
    }
}
