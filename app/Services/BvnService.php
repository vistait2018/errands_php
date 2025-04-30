<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 29/04/2025
 * Time: 11:28
 */

namespace App\Services;


use App\Models\Bvn;

class BvnService
{
    public function createBVN($data){
        $bvn_is_valid =   $this->validateBVN($data['bvn']);
        $createdBVN = Bvn::create($data);

        if(!$createdBVN){
            return new Exception('Error creating  New BVN');
        }
        return $createdNIN;
    }


   private  function validateBVN($bvn) {
        $is_eleven =  $this->validateBVNString($bvn);
        if($is_eleven == false){
            return new Exception('BVN is not a valid BVN');
        }
        //validate
    }

    private function validateBVNString($bvn):  boolean{
        $is_eleven =  preg_match('/^\d{11}$/', $bvn) === 1;
        if($is_eleven == false){
            return new Exception('BVN is not a valid BVN');
        }
    }

}