<?php

namespace App\Models;

use App\Models\Base\Department as BaseDepartment;

class Department extends BaseDepartment
{
	protected $fillable = [
		'name',
		'letter',
		'id_branch'
	];

    public static function getInfoById($id){
        return self::find($id);
    }

    public static function getInfoByName($name){
        return self::where('name', $name)
            ->first();
    }

    public function getFullNameAttribute() {
        return $this->name . ' / ' . $this->branch->name;
    }

    protected $casts = [
        'created_at'  => 'date:Y-m-d',
        'updated_at'  => 'date:Y-m-d',
    ];
}
