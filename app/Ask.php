<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ask extends Model
{
    protected $table = "ask";
    public $timestamps = false;

    protected $fillable = [
     'id', 'content', 'password','enable'
 ];


}
