<?php
namespace Depicter\Document\Models\Traits;

use Averta\Core\Utility\JSON;
use Exception;

trait DocumentAdminNoticeTrait {
	use HasDocumentIdTrait;
	use EntityPropertiesTrait;

	/**
	 * @var int|null
	 */
	protected $showAdminNotice = false;

	/**
	 * Gets document ID
	 *
	 * @return int|null
	 */
	public function showAdminNotice() {
		return $this->showAdminNotice;
	}

	/**
	 * Enables or disables unpublished notice of document
	 *
	 * @param int $showAdminNotice
	 *
	 * @return mixed
	 */
	public function setShowAdminNotice( $showAdminNotice = false ) {
		$this->showAdminNotice = $showAdminNotice;
		return $this;
	}

	/**
	 * Render unpublished changes notice
	 *
	 * @return string
	 */
	public function getUnpublishedChangesNotice() {
		if( ! $this->showAdminNotice() || ! $this->getDocumentID() ) {
			return '';
		}
		$markup = '';

		if ( $this->getEntityProperty('status') === 'draft' ) {
			$markup = \Depicter::view('admin/notices/slider-draft-notice')->with( 'view_args', [
				'isPublishedBefore' => \Depicter::documentRepository()->isPublishedBefore( $this->getDocumentID() ),
				'editUrl'           => \Depicter::editor()->getEditUrl( $this->getDocumentID() )
			])->toString();
		}

		$rule = \Depicter::metaRepository()->get( $this->getDocumentID(), 'rules', '' );
		if ( JSON::isJson( $rule ) ) {
			$rule = JSON::decode( $rule );
			if ( !empty( $rule->visibilitySchedule ) && !empty( $rule->visibilitySchedule->enable ) ) {
				$visibilityTime = $rule->visibilitySchedule;
				if ( !empty( $visibilityTime->start ) && ! \Depicter::schedule()->isDatePassed( $visibilityTime->start ) ) {
					$markup = \Depicter::view('admin/notices/slider-schedule-notice')->with( 'view_args', [
						'editUrl'           => \Depicter::editor()->getEditUrl( $this->getDocumentID() )
					])->toString();
				} else if ( !empty( $visibilityTime->end ) && \Depicter::schedule()->isDatePassed( $visibilityTime->end ) ) {
					$markup = \Depicter::view('admin/notices/slider-schedule-notice')->with( 'view_args', [
						'editUrl'           => \Depicter::editor()->getEditUrl( $this->getDocumentID() )
					])->toString();
				}
			}
		}

		return $markup;
	}

	/**
	 * Render expired subscription notice
	 *
	 * @return string
	 */
	public function getExpiredSubscriptionNotice() {
		if( ! $this->showAdminNotice() || ! $this->getDocumentID() ) {
			return '';
		}

		if ( ! \Depicter::auth()->isSubscriptionExpired() ) {
			return '';
		}

		try{
			// skip admin notice display for popup and notification bars
			if ( in_array( \Depicter::documentRepository()->findOne( $this->getDocumentID() )->getProperty('type'), ['popup', 'banner-bar', 'notification-bar'] ) ) {
				return '';
			}
		}catch( \Exception $e ){
			return '';
		}

		$subscription_id = \Depicter::options()->get('subscription_id' , '');
		return \Depicter::view('admin/notices/subscription-expire-notice')->with( 'view_args',[
			'subscription_id' => $subscription_id
		])->toString();
	}
}
