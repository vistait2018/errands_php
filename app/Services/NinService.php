<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 29/04/2025
 * Time: 11:15
 */

namespace App\Services;


use App\Models\Nin;

class NinService
{

    public function createNIN($data){
        $nin_is_valid =   $this->validateNin($data['nin']);
        $createdNIN = Nin::create($data);

        if(!$createdNIN){
            return new Exception('Error creating New NIN');
        }
        return $createdNIN;
    }

    private function validateNin($nin):  boolean{
        $nin_is_valid = $this->validateNINString($nin);
        if($nin_is_valid == false){
            return new Exception('NIN is not a valid NIN');
        }

        //verify with seamfix.com

    }

    private  function validateNINString($nin) {
        return preg_match('/^\d{11}$/', $nin) === 1;
    }


}