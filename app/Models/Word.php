<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
    use HasFactory;

    protected $table = 'words'; // Ensure Laravel knows the table name
    protected $fillable = ['german', 'english'];
    //
}
