<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 31 Mar 2019 06:12:32 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class MunicipalityWorker
 * 
 * @property int $id
 * @property int $municipality_id
 * @property string $name
 * @property string $position
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\Municipality $municipality
 *
 * @package App\Models
 */
class MunicipalityWorker extends Eloquent
{
	protected $table = 'municipality_worker';

	protected $casts = [
		'municipality_id' => 'int'
	];

	protected $fillable = [
		'municipality_id',
		'name',
		'position'
	];

	public function municipality()
	{
		return $this->belongsTo(\App\Models\Municipality::class);
	}

	public function __toString()
    {
        return $this->name;
    }
}
