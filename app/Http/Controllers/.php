<?php

namespace App\Http\Controllers;

use App\Services\RoleService;
use Illuminate\Http\Request;
use App\Services\ResponseService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class RoleController extends Controller
{
    protected $profileService;
    protected $responseService;

    public function __construct(RoleService $RoleService, ResponseService $responseService)
    {
        $this->RoleService = $RoleService;
        $this->responseService = $responseService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            return $this->responseService->success($this->RoleService->allRoles(),"Profile retrieved successfully", 200);
        }catch (Exception $ex) {
            return $this->responseService->error('Internal Server Error: ' . $ex->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        try{

            $validator = Validator::make($request->all(), [
                'Role_name' => 'required|string',
                'account_no' => 'required|string',
                'account_type' => 'required|string',

            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $validatedData = $validator->validated();

            $validatedData['user_id'] = Auth::user()->id;

            return $this->responseService->success($this->RoleService->createRole($validatedData,$validatedData['user_id']),"Role Info created successfully", 200);
        }catch (ValidationException $ex) {

            return $this->responseService->error($ex->getMessage(), 422);
        } catch (Exception $ex) {
            return $this->responseService->error('Internal Server Error: ' . $ex->getMessage(), 500);
        }catch (\Exception $ex) {
            return $this->responseService->error('Internal Server Error: ' . $ex->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $Role = $this->RoleService->getRoleById($id);
            If($Role == null){
                return $this->responseService->error("Role with ".$id." not found", 404,'Role_NOT_FOUND');
            }
            return $this->responseService->success(  $Role,"Role with ".$id."retrieved successfully", 200);
        }catch (Exception $ex) {
            return $this->responseService->error('Internal Server Error: ' . $ex->getMessage(), 500);
        } catch (\Exception $ex) {
            return $this->responseService->error('Internal Server Error: ' . $ex->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try{
            $validator = Validator::make($request->all(), [
                'Role_name' => 'required|string',
                'account_no' => 'required|string',
                'account_type' => 'required|string',

            ]);


            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            $validatedData = $validator->validated();
            $profile = $this-> RoleService->updateRole($validatedData,$id);
            If($profile == null){
                return $this->responseService->error("Role with ".$id." not found.Profile Could not be updated", 404,'PROFILE_TO_UPDATE_NOT_FOUND');
            }
            return $this->responseService->success( $profile,"Role updated successfully", 200);
        }catch (ValidationException $ex) {

            return $this->responseService->error($ex->getMessage(), 422);
        }  catch (\Exception $ex) {
            return $this->responseService->error('Internal Server Error: ' . $ex->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            return $this->responseService->success($this->RoleService->delete($id),"Role with ".$id." deleted successfully", 200);
        } catch (\Exception $ex) {
            return $this->responseService->error('Internal Server Error: ' . $ex->getMessage(), 500);
        }
    }
}
