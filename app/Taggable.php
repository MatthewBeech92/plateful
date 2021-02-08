<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Taggable extends Model
{
    public $timestamps = false;
    protected $fillable = ['tag_id', 'taggable_type', 'taggable_id'];

}
