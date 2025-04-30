<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 29/04/2025
 * Time: 15:01
 */

namespace App\Services;
use Illuminate\Support\Facades\Http;



class NetworkService
{
   public function check(){
       try {
           $response = Http::get('https://www.google.com');
           if ($response->successful()) {
               return true;
           } else {
             return false;
           }
       } catch (\Exception $e) {
          return false;
       }

   }
}