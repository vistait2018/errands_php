<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 29/04/2025
 * Time: 11:36
 */

namespace App\Services;


use App\Models\Role;

class RoleService
{
    public function createRole($data){
        $createdRole = Role::create($data);
        if(!$createdRole){
            return new Exception('Error creating not create New Role');
        }
        return $createdRole;
    }

    public function updateRole($data ,$id){
        $roleToUpdate =  $this->getRoleById($id);
        $updateRole = $roleToUpdate->merge($data);
        $updateRole->save();
        If(!$updateRole){
            return new Exception('Role could not be updated');
        }

        return $updateRole;

    }
    public function allRole(){
        $roles = Role::all();
        if($roles->count() >= 1){
            return $roles;
        }
        return null;
    }

    public function getRoleById($id){
        $roleToUpdate = Role::findOrFail($id);
        if(!$roleToUpdate){
            return new Exception('Role with id '. $id .' not found');
        }
        return $roleToUpdate;
    }

    public function delete($id){
        $roleToDelete = $this->getRoleById($id);
        $roleToDelete->delete();
    }
}