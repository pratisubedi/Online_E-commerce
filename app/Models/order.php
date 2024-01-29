<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\orderItem;

class order extends Model
{
    use HasFactory;
    protected $table="orders";

    public function items(){
        return $this->hasMany(orderItem::class);
    }

}
