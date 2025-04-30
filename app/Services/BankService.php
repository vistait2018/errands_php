<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 29/04/2025
 * Time: 11:36
 */

namespace App\Services;


use App\Models\Bank;

class BankService
{
    public function createBank($data){
        $createdBank = Bank::create($data);
        if(!$createdBank){
            return new Exception('Error creating not create New Bank');
        }
        return $createdBank;
    }

    public function updateBank($data ,$id){
        $bankToUpdate =  $this->getBankById($id);
        $updateBank = $bankToUpdate->merge($data);
        $updateBank->save();
        If(!$updateBank){
            return new Exception('Bank could not be updated');
        }

        return $updateBank;

    }
    public function allBank(){
        $banks = Bank::all();
        if($banks->count() >= 1){
            return $banks;
        }
        return null;
    }

    public function getBankById($id){
        $bankToUpdate = Bank::findOrFail($id);
        if(!$bankToUpdate){
            return new Exception('Bank with id '. $id .' not found');
        }
        return $bankToUpdate;
    }

    public function delete($id){
        $bankToDelete = $this->getBankById($id);
        $bankToDelete->delete();
    }
}