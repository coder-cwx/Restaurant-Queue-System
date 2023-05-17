<?php

namespace App\Models;

use App\Models\Base\TokenNumber as BaseTokenNumber;

class TokenNumber extends BaseTokenNumber
{
	protected $hidden = [
		'secret_number'
	];

	protected $fillable = [
		'id_department',
		'id_counter',
		'secret_number',
		'date',
		'number',
		'status',
		'crypt',
		'is_new',
		'id_user',
		'email_client',
        'phone_client',
		'id_branch'
	];

	protected $casts = [
        'date'  => 'date:Y-m-d',
    ];
}
