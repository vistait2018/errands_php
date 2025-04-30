<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 29/04/2025
 * Time: 11:37
 */

namespace App\Services;
use App\Models\Comment;


class CommentService
{
    public function createComment($data){
        $createdComment = Comment::create($data);
        if(!$createdComment){
            return new Exception('Error creating not create New Comment');
        }
        return $createdComment;
    }

    public function updateComment($data ,$id){
        $commentToUpdate =  $this->getCommentById($id);
        $updateComment = $commentToUpdate->merge($data);
        $updateComment->save();
        If(!$updateComment){
            return new Exception('Comment could not be updated');
        }

        return $updateComment;

    }
    public function allComment(){
        $comments = Comment::all();
        if($comments->count() >= 1){
            return $comments;
        }
        return null;
    }

    public function getCommentById($id){
        $commentToUpdate = Comment::findOrFail($id);
        if(!$commentToUpdate){
            return new Exception('Comment with id '. $id .' not found');
        }
        return $commentToUpdate;
    }

    public function delete($id){
        $commentToDelete = $this->getCommentById($id);
        $commentToDelete->delete();
    }
}