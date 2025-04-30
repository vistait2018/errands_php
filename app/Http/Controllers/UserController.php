<?php
/**
 * Jide Solanke.
 * User: USER
 * Date: 29/04/2025
 * Time: 11:47
 */

namespace App\Http\Controllers;


use App\Services\ResponseService;
use App\Services\UserService;
use http\Exception;

class UserController extends Controller
{

    protected $responseService;
    protected $userService;

    public function __construct(ResponseService $responseService , UserService $userService)
    {
        $this->responseService = $responseService;
        $this->userService = $userService;
    }

    public function index(){
        try{
            $users = $this->userService->all();
            if($users != null){
                return $this->responseService->success($users, 'Users retrieved successful', 200);
            }
            return $this->responseService->success([], 'No users Available ', 200);
        }catch (Exception $ex){
            return $this->responseService->error('Internal Server Error: ' . $ex->getMessage(), 500);
        }

    }

    public function me(){
        try{
            $user = $this->userService->me();
            if($user!= null){
                return $this->responseService->success($user, 'User retrieved successful', 200);
            }
            return $this->responseService->success([], 'No user Available ', 200);
        }catch (Exception $ex){
            return $this->responseService->error('Internal Server Error: ' . $ex->getMessage(), 500);
        }
    }
}