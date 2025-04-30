<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 29/04/2025
 * Time: 11:36
 */

namespace App\Services;


use App\Models\Bank;
use App\Models\User;


class BankService
{
    public function createBank($data,$user_id){
        $user =  User::find($user_id);
        if(!$user){
            throw new Exception('Error creating new bank infon');
        }
        $createdBank = Bank::create($data);
        if(!$createdBank){
            throw new Exception('Error creating not create New Bank');
        }
        return $createdBank;
    }

    public function updateBank($data ,$id){
        $bankToUpdate =  $this->getBankById($id);
        $updateBank = $bankToUpdate->fill($data);
        $updateBank->save();
        If(!$updateBank){
            throw new Exception('Bank could not be updated');
        }

        return $updateBank;

    }
    public function allBanks(){
      return  $banks = Bank::all();

    }

    public function getBankById($id){
        $bankToUpdate = Bank::findOrFail($id);
        if(!$bankToUpdate){
            throw new Exception('Bank with id '. $id .' not found');
        }
        return $bankToUpdate;
    }

    public function delete($id){
        $bankToDelete = $this->getBankById($id);
        $bankToDelete->delete();
    }
}