<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ignores extends Model
{
    use HasFactory;
    protected $table = 'ignore';
    protected $guarded = [];
}
