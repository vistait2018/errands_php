<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 29/04/2025
 * Time: 11:50
 */

namespace App\Services;


use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserService
{

    public function all(){
        if (!Auth::user()->role) {
           return new \Exception('No role assigned to the user');
        }

        return User::paginate(10);

    }

    public function me(){
        $user = Auth::user()->id;
        $auth_user = User::findOrFail($user);
      return  $auth_user->with(['role','profile','bank','bvn','nin','level','comments','ratings'])->get();

    }

}