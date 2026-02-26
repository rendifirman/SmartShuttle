<?php
namespace App\Http\Controllers\API;

class TestPaymentController
{
    public function devPaylabsVaCreate($request)
    {
        try {
            $validated = $request->validate([
                'requestId' => 'sometimes|string',
                'merchantId' => 'sometimes|string',
            ]);

            $payload = array_merge([
                'requestId' => $validated['requestId'] ?? 'default',
            ], $validated);

            if (empty($payload['storeId'])) {
                unset($payload['storeId']);
            }

            $result = [];
            return response()->json($result);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
?>
