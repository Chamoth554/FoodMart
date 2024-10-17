<?php
namespace Depicter\Database\Entity;

use Averta\WordPress\Database\Entity\Model;

class Lead extends Model
{

	protected $idColumn = 'id';

	protected $idForeign = 'lead_id';

	/**
	 * Resource name.
	 *
	 * @var string
	 */
	protected $resource = 'depicter_leads';

	/**
	 * Retrieved the joined table name
	 *
	 * @var string
	 */
	protected string $joined = '';

	/**
	 * Determines what fields can be saved without be explicitly.
	 *
	 * @var array
	 */
	protected $builtin = [
		'source_id',
		'content_id',
		'content_name',
		'created_at'
	];

	protected $guard = [ 'id' ];

	protected $format = [
		'created_at'  => 'currentDateTime'
	];

	public function currentDateTime() {
        return gmdate('Y-m-d H:i:s', time());
    }

	public function fields(){
		return $this->hasMany(LeadField::class, $this->idForeign, $this->idColumn );
	}

}
