<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 29/04/2025
 * Time: 11:37
 */

namespace App\Services;


use App\Models\Rating;

class RatingService
{
    public function createRating($data){
        $createdRating = Rating::create($data);
        if(!$createdRating){
            return new Exception('Error creating not create New Rating');
        }
        return $createdRating;
    }

    public function updateRating($data ,$id){
        $ratingToUpdate =  $this->getRatingById($id);
        $updateRating = $ratingToUpdate->merge($data);
        $updateRating->save();
        If(!$updateRating){
            return new Exception('Rating could not be updated');
        }

        return $updateRating;

    }
    public function allRating(){
        $ratings = Rating::all();
        if($ratings->count() >= 1){
            return $ratings;
        }
        return null;
    }

    public function getRatingById($id){
        $ratingToUpdate = Rating::findOrFail($id);
        if(!$ratingToUpdate){
            return new Exception('Rating with id '. $id .' not found');
        }
        return $ratingToUpdate;
    }

  
}