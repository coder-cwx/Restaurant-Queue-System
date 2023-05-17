<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Counter;
use App\Models\Department;
use App\Models\TokenNumber;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Branch
 * 
 * @property int $id
 * @property string $name
 * @property string $address
 * @property string $email
 * @property string $phone
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Collection|Counter[] $counters
 * @property Collection|Department[] $departments
 * @property Collection|TokenNumber[] $token_numbers
 * @property Collection|User[] $users
 *
 * @package App\Models\Base
 */
class Branch extends Model
{
	protected $table = 'branches';

	public function counters()
	{
		return $this->hasMany(Counter::class, 'id_branch');
	}

	public function departments()
	{
		return $this->hasMany(Department::class, 'id_branch');
	}

	public function token_numbers()
	{
		return $this->hasMany(TokenNumber::class, 'id_branch');
	}

	public function users()
	{
		return $this->hasMany(User::class, 'id_branch');
	}
}
