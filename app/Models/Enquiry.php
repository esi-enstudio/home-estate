<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 */
class Enquiry extends Model
{
    protected $fillable = ['property_id','name','email','phone','message','user_id','is_read'];
}
