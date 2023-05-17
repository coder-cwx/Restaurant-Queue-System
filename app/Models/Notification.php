<?php

namespace App\Models;

use App\Models\Base\Notification as BaseNotification;

class Notification extends BaseNotification
{
	protected $fillable = [
		'type',
		'notifiable_type',
		'notifiable_id',
		'data',
		'read_at'
	];
}
