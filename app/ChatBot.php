<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatBot extends Model
{
    protected $table = "chatbot";
    public $timestamps = false;

    protected $fillable = [
     'ask','answer'
 	];


}
