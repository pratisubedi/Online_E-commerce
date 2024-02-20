<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class staticPage extends Model
{
    use HasFactory;
    protected  $table="static_pages";
    protected $fillable=['name','slug','content'];
}
