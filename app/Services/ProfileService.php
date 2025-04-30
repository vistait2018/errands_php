<?php
namespace App\Services;

use App\Models\Profile;
use App\Models\User;
use Exception;

class ProfileService {
    public function createProfile($data,$id)
    {
      $user =  User::find($id);
      if(!$user){
          throw new Exception('Error creating new profile');
      }
        $createdProfile = Profile::firstOrCreate($data);
            if (!$createdProfile) {
            throw new Exception('Error creating new profile');
            }
        return $createdProfile;
    }

    public function updateProfile($data, $id)
    {
        $profileToUpdate = $this->getProfileById($id);
        if($profileToUpdate == null){
            return null;
        }
        $profileToUpdate->fill($data);
        $profileToUpdate->save();
        return $profileToUpdate;
    }

    public function allProfile()
    {
        return Profile::paginate(10);
    }

    public function getProfileById($id)
    {
       $profile = Profile::find($id);
       if($profile){
           return $profile;
       }
       return null;
    }

    public function delete($id)
    {
        $profileToDelete = $this->getProfileById($id);

        if ($profileToDelete == null) {
            throw new Exception('Profile could not be deleted');
        }
        $profileToDelete->delete();
        return true; // or return $profileToDelete;
    }


}

