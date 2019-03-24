<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Flat extends Model
{

    public $rules = [
	'city' => 'required',
	'district' => 'required',
	'address' => 'required',
	'residence' => 'required',
	'block' => 'required',
	'floors' => 'required',
	'floor' => 'required',
	'rooms' => 'required',
	'square' => 'required',
	'rant' => 'required',
    ];
    protected $fillable = [
	'city',
	'district',
	'address',
	'residence',
	'block',
	'floors',
	'floor',
	'rooms',
	'square',
	'rant'
    ];

}