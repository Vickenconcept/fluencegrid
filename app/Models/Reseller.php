<?php

namespace App\Models;

use App\Models\Scopes\DataAccessScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Reseller extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user(){

       return $this->belongsTo(User::class);
    }
   
}
