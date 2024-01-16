<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class couponsModel extends Model
{
    use HasFactory;
    protected $table = "discount_coupons";
    protected $fillable = ['code','name','description','max_uses','max_uses_user','type','discount_amount','min_amount','status','starts_at','expire_at'] ;
}
