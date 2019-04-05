<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 31 Mar 2019 06:12:32 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Address
 * 
 * @property int $id
 * @property string $street
 * @property string $zip
 * @property string $city_name
 * @property float $longitude
 * @property float $latitude
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $municipalities
 *
 * @package App\Models
 */
class Address extends Eloquent
{
	protected $table = 'address';

	protected $casts = [
		'longitude' => 'float',
		'latitude' => 'float'
	];

	protected $fillable = [
		'street',
		'zip',
		'city_name',
		'longitude',
		'latitude'
	];

	public function municipalities()
	{
		return $this->hasMany(\App\Models\Municipality::class);
	}
}
