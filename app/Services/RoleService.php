<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 29/04/2025
 * Time: 11:36
 */

namespace App\Services;


use App\Models\Role;
use App\Models\User;

class RoleService
{
    public function createRole($data ){

        $createdRole = Role::create($data);
        if(!$createdRole){
            return new Exception('Error creating not create New Role');
        }
        return $createdRole;
    }

    public function updateRole($data ,$id){
        $roleToUpdate =  $this->getRoleById($id);
        $updateRole = $roleToUpdate->fill($data);
        $updateRole->save();
        If(!$updateRole){
            return new Exception('Role could not be updated');
        }

        return $updateRole;

    }
    public function allRoles(){
      return  $roles = Role::all();

    }

    public function getRoleById($id){
        $roleToUpdate = Role::find($id);
        if( $roleToUpdate){
            return  $roleToUpdate;
        }
        return null;
    }

    public function delete($id){
        $roleToDelete = $this->getRoleById($id);
        $roleToDelete->delete();
    }
}