<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Setting
 * 
 * @property int $id
 * @property string $marquee
 * @property string $twilio_sid
 * @property string $twilio_token
 * @property string $twilio_number
 *
 * @package App\Models\Base
 */
class Setting extends Model
{
	protected $table = 'settings';
	public $timestamps = false;
}
