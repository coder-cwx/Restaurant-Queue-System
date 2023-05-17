<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Branch;
use App\Models\TokenNumber;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 * 
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon $email_verified_at
 * @property string $password
 * @property string $remember_token
 * @property string $image
 * @property int $role
 * @property bool $is_online
 * @property int $id_branch
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Branch $branch
 * @property Collection|TokenNumber[] $token_numbers
 *
 * @package App\Models\Base
 */
class User extends Model
{
	protected $table = 'users';

	protected $casts = [
		'role' => 'int',
		'is_online' => 'bool',
		'id_branch' => 'int'
	];

	protected $dates = [
		'email_verified_at'
	];

	public function branch()
	{
		return $this->belongsTo(Branch::class, 'id_branch');
	}

	public function token_numbers()
	{
		return $this->hasMany(TokenNumber::class, 'id_user');
	}
}
