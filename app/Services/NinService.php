<?php

namespace App\Services;

use App\Models\Nin;
use Illuminate\Support\Facades\Http;
use Exception;

class NinService
{
    public function createNIN(array $data)
    {
        $isValid = $this->verifyNIN($data['nin']);
        $data['is_valid'] = $isValid;

        $createdNIN = Nin::create($data);

        if (!$createdNIN) {
            throw new Exception('Error creating new NIN');
        }

        return $createdNIN;
    }

    public function verifyNIN(string $nin)
    {
        $apiKey = env('PAYSTACK_API_KEY'); // Use environment variable instead of hardcoding
        $apiEndpoint = 'https://api.paystack.co/bank/resolve_NIN'; // Double-check Paystack's actual endpoint

        $response = Http::withOptions([
            'verify' => false, // ✅ correct place
        ])->withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
        ])->get($apiEndpoint, [
            'nin' => $nin,
        ]);
        if ($response->successful()) {
            $data = $response->json();

            if ($data['status'] === true) {
                // Optionally match returned data with user's profile
                return true;
            }

            throw new Exception('NIN is not a valid NIN');
        }

        throw new Exception('Failed to verify NIN');
    }
}
