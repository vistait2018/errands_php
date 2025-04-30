<?php

namespace App\Http\Controllers;

use App\Services\BankService;
use Illuminate\Http\Request;
use App\Services\ResponseService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class BankController extends Controller
{
    protected $profileService;
    protected $responseService;

    public function __construct(BankService $bankService, ResponseService $responseService)
    {
        $this->bankService = $bankService;
        $this->responseService = $responseService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            return $this->responseService->success($this->bankService->allBanks(),"Profile retrieved successfully", 200);
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
                'bank_name' => 'required|string',
                'account_no' => 'required|string',
                'account_type' => 'required|string',

            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $validatedData = $validator->validated();

            $validatedData['user_id'] = Auth::user()->id;

            return $this->responseService->success($this->bankService->createBank($validatedData,$validatedData['user_id']),"Bank Info created successfully", 200);
        }catch (ValidationException $ex) {

            return $this->responseService->error($ex->getMessage(), 422);
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
            $bank = $this->bankService->getBankById($id);
            If($bank == null){
                return $this->responseService->error("Bank with ".$id." not found", 404,'BANK_NOT_FOUND');
            }
            return $this->responseService->success(  $bank,"Bank with ".$id."retrieved successfully", 200);
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
                'bank_name' => 'required|string',
                'account_no' => 'required|string',
                'account_type' => 'required|string',

            ]);


            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            $validatedData = $validator->validated();
            $profile = $this-> bankService->updateBank($validatedData,$id);
            If($profile == null){
                return $this->responseService->error("Bank with ".$id." not found.Profile Could not be updated", 404,'PROFILE_TO_UPDATE_NOT_FOUND');
            }
            return $this->responseService->success( $profile,"Bank updated successfully", 200);
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
            return $this->responseService->success($this->bankService->delete($id),"Bank with ".$id." deleted successfully", 200);
        } catch (\Exception $ex) {
            return $this->responseService->error('Internal Server Error: ' . $ex->getMessage(), 500);
        }
    }
}
