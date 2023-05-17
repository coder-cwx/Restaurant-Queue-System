<?php

namespace App\Models;

use App\Models\Base\Setting as BaseSetting;

class Setting extends BaseSetting
{
	protected $hidden = [
		'twilio_token'
	];

	protected $fillable = [
		'marquee',
		'twilio_sid',
		'twilio_token',
		'twilio_number'
	];
}
