<?php

namespace App\Http\Controllers;

use App\Services\BvnService;
use App\Services\NinService;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class BvnAndNinController extends Controller
{
   protected $bvnService;
   protected  $ninService;
   protected $responseService;

   public function __construct( BvnService $bvnService, NinService $ninService, ResponseService $responseService)
   {
       $this->responseService = $responseService;
       $this->bvnService = $bvnService;
       $this->ninService = $ninService;
   }

   public function validateAndCreateBVN(Request $request){
       try{
           $user_id = Auth::user()->id;
           $validator = Validator::make($request->all(), [
               'bvn' => 'required|string|min:11|max:11',

           ]);

           if ($validator->fails()) {
               throw new ValidationException($validator);
           }

           $validatedData = $validator->validated();
           $validatedData['user_id'] = $user_id;
           return $this->responseService->success($this->bvnService->createBVN($validatedData),"BVN Validated Successfully", 200);
       }catch (ValidationException $ex) {
           return $this->responseService->error($ex->getMessage(), 422);
       }catch (\Exception $ex) {
           return $this->responseService->error('Internal Server Error: ' . $ex->getMessage(), 500);
       }

   }

    public function validateAndCreateNIN(Request $request){
        try{
            $user_id = Auth::user()->id;
            $validator = Validator::make($request->all(), [
                'nin' => 'required|string|min:11|max:11',

            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $validatedData = $validator->validated();
            $validatedData['user_id'] = $user_id;
            return $this->responseService->success($this->ninService->createNIN($validatedData),"BVN Validated Successfully", 200);
        }catch (ValidationException $ex) {
            return $this->responseService->error($ex->getMessage(), 422);
        }catch (\Exception $ex) {
            return $this->responseService->error('Internal Server Error: ' . $ex->getMessage(), 500);
        }

    }

    public function verifyBVN(Request $request){
        try{

            $validator = Validator::make($request->all(), [
                'bvn' => 'required|string|min:11|max:11',

            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $validatedData = $validator->validated();

            return $this->responseService->success($this->bvnService->verifyBvn($validatedData['bvn']),"BVN Validated Successfully", 200);
        }catch (ValidationException $ex) {
            return $this->responseService->error($ex->getMessage(), 422);
        }catch (\Exception $ex) {
            return $this->responseService->error('Internal Server Error: ' . $ex->getMessage(), 500);
        }
    }
    public function verifyNIN(Request $request){
        try{

            $validator = Validator::make($request->all(), [
                'nin' => 'required|string|min:11|max:11',

            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $validatedData = $validator->validated();

            return $this->responseService->success($this->ninService->verifyNIN($validatedData['nin']),"NIN Validated Successfully", 200);
        }catch (ValidationException $ex) {
            return $this->responseService->error($ex->getMessage(), 422);
        }catch (\Exception $ex) {
            return $this->responseService->error('Internal Server Error: ' . $ex->getMessage(), 500);
        }
    }
}
