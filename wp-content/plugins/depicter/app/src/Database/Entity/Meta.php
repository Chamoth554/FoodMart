<?php
namespace Depicter\Database\Entity;

use Averta\WordPress\Database\Entity\Model;

class Meta extends Model
{
	/**
	 * Resource name.
	 *
	 * @var string
	 */
	protected $resource = 'depicter_meta';

	/**
	 * Determines what fields can be saved without be explicitly.
	 *
	 * @var array
	 */
	protected $builtin = [
		'relation',
		'relation_id',
		'meta_key',
		'meta_value'
	];

	protected $guard = [ 'id' ];

	public function document()
    {
        return $this->belongsTo(Document::class, 'relation_id', 'id');
    }
}
