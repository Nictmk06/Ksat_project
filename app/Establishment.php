<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Establishment extends Model
{
     public static function getEstablishment(){
    		$value=DB::table('establishment')->get();
   	 		return $value;
  		}
}
