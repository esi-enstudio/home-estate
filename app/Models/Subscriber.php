<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $validatedData)
 */
class Subscriber extends Model
{
    protected $fillable = ['email'];
}
