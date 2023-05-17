<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Branch;
use App\Models\Counter;
use App\Models\Department;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TokenNumber
 * 
 * @property int $id
 * @property int $id_department
 * @property int $id_counter
 * @property int $secret_number
 * @property Carbon $date
 * @property int $number
 * @property string $status
 * @property string $crypt
 * @property bool $is_new
 * @property int $id_user
 * @property string $email_client
 * @property string $phone_client
 * @property int $id_branch
 * 
 * @property Branch $branch
 * @property Counter $counter
 * @property Department $department
 * @property User $user
 *
 * @package App\Models\Base
 */
class TokenNumber extends Model
{
	protected $table = 'token_numbers';
	public $timestamps = false;

	protected $casts = [
		'id_department' => 'int',
		'id_counter' => 'int',
		'secret_number' => 'int',
		'number' => 'int',
		'is_new' => 'bool',
		'id_user' => 'int',
		'id_branch' => 'int'
	];

	protected $dates = [
		'date'
	];

	public function branch()
	{
		return $this->belongsTo(Branch::class, 'id_branch');
	}

	public function counter()
	{
		return $this->belongsTo(Counter::class, 'id_counter');
	}

	public function department()
	{
		return $this->belongsTo(Department::class, 'id_department');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'id_user');
	}
}
