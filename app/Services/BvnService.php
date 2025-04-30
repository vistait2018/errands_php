<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 29/04/2025
 * Time: 11:28
 */

namespace App\Services;


use App\Models\Bvn;
use Illuminate\Support\Facades\Http;
use Exception;
class BvnService
{
    public function createBVN($data){
        $bvn_is_valid =   $this->verifyBvn($data['bvn']);
        $data['is_valid'] =$bvn_is_valid;
        $createdBVN = Bvn::create($data);

        if(!$createdBVN){
            throw new Exception('Error creating  New BVN');
        }
        return $createdBVN;
    }



    public function verifyBvn($bvn):boolean
    {

        $apiKey = 'YOUR_PAYSTACK_API_KEY';
        $apiEndpoint = 'http://api.paystack.co/bank/resolve_bvn';

        try {
            $response = Http::withOptions([
                'verify' => false, // ✅ correct place
            ])->withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
            ])->get($apiEndpoint, [
                'bvn' => $bvn,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] === true) {
                    // Check if NIN name is the profile name and other information
                    return true;
                } else {
                    throw new Exception('BVN is not a valid NIN');
                }
            } else {
                throw new Exception('Failed to verify BVN');
            }
        } catch (\Exception $e) {
            throw new Exception('Failed to verify BVN: ' . $e->getMessage());
        }

    }


}