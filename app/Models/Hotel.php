<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'partner_id',
        'name',
        'content',
        'owner',
        'reg_no',
        'open_date',
        'address',
        'tel',
        'fax',
        'email',
        'traffic',
        'level',
        'created_at'
    ];
}
