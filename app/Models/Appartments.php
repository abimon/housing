<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appartments extends Model
{
    use HasFactory;
    protected $fillable = [
        'cover_path',
        'user_id',
        'category',
        'location',
        'price',
        'description','uniq_id'
    ];
    function owner(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}
