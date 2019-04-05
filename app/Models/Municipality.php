<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 31 Mar 2019 06:12:32 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Municipality
 * 
 * @property int $id
 * @property int $address_id
 * @property string $url_id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $fax
 * @property string $web
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\Address $address
 * @property \Illuminate\Database\Eloquent\Collection $municipality_workers
 *
 * @package App\Models
 */
class Municipality extends Eloquent
{
	protected $table = 'municipality';

	protected $casts = [
		'address_id' => 'int'
	];

	protected $fillable = [
		'address_id',
		'url_id',
		'name',
		'email',
		'phone',
		'fax',
		'web'
	];

	public function address()
	{
		return $this->belongsTo(\App\Models\Address::class);
	}

	public function municipality_worker()
	{
		return $this->belongsTO(\App\Models\MunicipalityWorker::class);
	}

	public function municipality_workers()
	{
		return $this->hasMany(\App\Models\MunicipalityWorker::class);
	}
}
