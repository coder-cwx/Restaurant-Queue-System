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
 * Class Counter
 * 
 * @property int $id
 * @property string $name
 * @property int $id_branch
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Branch $branch
 * @property Collection|TokenNumber[] $token_numbers
 *
 * @package App\Models\Base
 */
class Counter extends Model
{
	protected $table = 'counters';

	protected $casts = [
		'id_branch' => 'int'
	];

	public function branch()
	{
		return $this->belongsTo(Branch::class, 'id_branch');
	}

	public function token_numbers()
	{
		return $this->hasMany(TokenNumber::class, 'id_counter');
	}
}
