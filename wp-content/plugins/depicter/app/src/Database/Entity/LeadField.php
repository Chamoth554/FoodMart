<?php
namespace Depicter\Database\Entity;

use Averta\WordPress\Database\Entity\Model;

class LeadField extends Model
{
	protected $idColumn = 'id';

	protected $idLocal = 'lead_id';

	/**
	 * Resource name.
	 *
	 * @var string
	 */
	protected $resource = 'depicter_lead_fields';

	/**
	 * Determines what fields can be saved without be explicitly.
	 *
	 * @var array
	 */
	protected $builtin = [
		'lead_id',
		'name',
		'type',
		'value',
		'created_at',
		'updated_at'
	];

	protected $guard = [ 'id' ];

	protected $format = [
		'created_at'  => 'currentDateTime',
		'updated_at'  => 'currentDateTime'
	];

	public function currentDateTime() {
        return gmdate('Y-m-d H:i:s', time());
    }

	public function lead(){
		return $this->belongsTo(Lead::class, $this->idLocal, $this->idColumn );
	}
}
