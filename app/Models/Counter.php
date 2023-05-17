<?php

namespace App\Models;

use App\Models\Base\Counter as BaseCounter;

class Counter extends BaseCounter
{
	protected $fillable = [
		'name',
		'id_branch'
	];

    public function getFullNameAttribute() {
        return $this->name . ' / ' . $this->branch->name;
    }

	protected $casts = [
        'created_at'  => 'date:Y-m-d',
        'updated_at'  => 'date:Y-m-d',
    ];
}
