<?php

namespace App\Http\Controllers;

use App\Services\ProfileService;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;



class ProfileController extends Controller
{

    protected $profileService;
    protected $responseService;

    public function __construct(ProfileService $profileService, ResponseService $responseService)
    {
        $this->profileService = $profileService;
        $this->responseService = $responseService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       try{
        return $this->responseService->success($this->profileService->allProfile(),"Profile retrieved successfully", 200);
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
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'middle_name' => 'nullable|string',
                'sex' => 'required|string',
                'phone_no' => 'required|string',
                'dob' => ['required', 'date_format:Y-m-d', function ($attribute, $value, $fail) {
                    if (!Carbon::createFromFormat('Y-m-d', $value)->isBefore(Carbon::now()->subYears(18))) {
                        $fail('You must be at least 18 years old.');
                    }
                }],
                'address' => 'required|string|min:5',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $validatedData = $validator->validated();

            $validatedData['user_id'] = Auth::user()->id;

            return $this->responseService->success($this->profileService->createProfile($validatedData),"Profile retrieved successfully", 200);
        }catch (ValidationException $ex) {

            return $this->responseService->error($ex->getMessage(), 422);
        } catch (Exception $ex) {
            return $this->responseService->error('Internal Server Error: ' . $ex->getMessage(), 500);
        }catch (\Exception $ex) {
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
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'middle_name' => 'nullable|string',
                'sex' => 'required|string',
                'phone_no' => 'required|string',
                'dob' => ['required', 'date_format:Y-m-d', function ($attribute, $value, $fail) {
                    if (!Carbon::createFromFormat('Y-m-d', $value)->isBefore(Carbon::now()->subYears(18))) {
                        $fail('You must be at least 18 years old.');
                    }
                }],
                'address' => 'required|string|min:5',

            ]);


            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            $validatedData = $validator->validated();
            $profile = $this->profileService->updateProfile($validatedData,$id);
            If($profile == null){
                return $this->responseService->error("Profile with ".$id." not found", 404,'PROFILE_NOT_FOUND');
            }
            return $this->responseService->success($profile,"Profile updated successfully", 200);
        }catch (ValidationException $ex) {

            return $this->responseService->error($ex->getMessage(), 422);
        } catch (Exception $ex) {
            return $this->responseService->error('Internal Server Error: ' . $ex->getMessage(), 500);
        } catch (\Exception $ex) {
             return $this->responseService->error('Internal Server Error: ' . $ex->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            return $this->responseService->success($this->profileService->delete($id),"Profile with ".$id." deleted successfully", 200);
        }catch (Exception $ex) {
            return $this->responseService->error('Internal Server Error: ' . $ex->getMessage(), 500);
        } catch (\Exception $ex) {
            return $this->responseService->error('Internal Server Error: ' . $ex->getMessage(), 500);
        }
    }


    public function show(string $id){
        try {
            $profile = $this->profileService->getProfileById($id);
            If($profile == null){
                return $this->responseService->error("Profile with ".$id." not found", 404,'PROFILE_NOT_FOUND');
            }
            return $this->responseService->success( $profile,"Profile with ".$id."retrieved successfully", 200);
        }catch (Exception $ex) {
            return $this->responseService->error('Internal Server Error: ' . $ex->getMessage(), 500);
        } catch (\Exception $ex) {
            return $this->responseService->error('Internal Server Error: ' . $ex->getMessage(), 500);
        }
    }
}
