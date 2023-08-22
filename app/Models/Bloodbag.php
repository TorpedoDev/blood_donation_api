<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bloodbag extends Model
{
    use HasFactory;
    protected $fillable = ['user_id' , 'content' , 'address' , 'phone' , 'blood_type' , 'bag_num'];
}
