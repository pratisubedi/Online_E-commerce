<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class customerAddress extends Model
{
    use HasFactory;
    protected  $table="customer_address";
    protected $fillable = ['first_name','last_name','address','email','city','state','user_id','zip','Appartment','Mobile','country_id'] ;
}
