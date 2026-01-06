<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\PaylabsService;
use Illuminate\Support\Facades\Log;

class VerifyPaylabsSignature
{
    protected $paylabsService;

    public function __construct(PaylabsService $paylabsService)
    {
        $this->paylabsService = $paylabsService;
    }

    public function handle(Request $request, Closure $next)
    {
        // Skip signature verification for testing
        if (config('paylabs.testing.enabled') && $request->has('test_mode')) {
            return $next($request);
        }

        $signature = $request->input('signature');

        if (!$signature) {
            Log::warning('Paylabs callback missing signature');
            return response()->json(['success' => false, 'message' => 'Missing signature'], 400);
        }

        $data = $request->except('signature');

        if (!$this->paylabsService->verifySignature($data, $signature)) {
            Log::error('Paylabs signature verification failed', [
                'signature_received' => $signature,
                'data' => $data
            ]);

            return response()->json(['success' => false, 'message' => 'Invalid signature'], 400);
        }

        return $next($request);
    }
}
