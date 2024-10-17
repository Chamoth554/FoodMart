<?php
namespace Depicter\Document\Models\Elements;

use Averta\Core\Utility\Arr;
use Depicter\Document\CSS\Selector;
use Depicter\Document\Models;
use Depicter\Document\Models\Common\Styles;
use Depicter\Html\Html;

class Form extends Models\Element
{
	/**
	 * @throws \JsonMapper_Exception
	 */
	public function render() {
		$args = $this->getDefaultAttributes();
		$output = '';

		// If it was parent form element.
		if ( ! $this->componentType ) {
			$output = Html::form( $this->getSubmitPath(), 'post', $args, "\n" . $this->getFormContent() );

		// Otherwise, it's input and buttons inside the form
		} else {
			$args['data-type'] = $this->componentType;
			switch ( $this->componentType ) {
				case 'form:input':
					$output = Html::div( $args, $this->getInputContent() );
					break;
				case 'form:submit':
					$output = Html::button( $args, $this->getContent() );

					break;
				case 'form:message':
					$output = Html::div( $args, $this->getMessageContent() );
					break;
				default:
					break;
			}
		}

		return $output . "\n";
	}

	/**
	 * Get CSS class names of this element
	 *
	 * @return array
	 */
	public function getClassNamesList(): array{
		$classes = parent::getClassNamesList();
		if ( ! $this->componentType ) {
			$classes[] = ( !empty( $this->options->labelsPlacement ) && 'left' === $this->options->labelsPlacement ) ? Selector::prefixify( 'label-left' ) : Selector::prefixify( 'label-top' );
		} else if ( $this->componentType == 'form:input' ) {
			$classes[] = Selector::prefixify( 'form-field' );
		}
		return $classes;
	}

	/**
	 * Retrieves the content of element
	 *
	 * @return string
	 * @throws \JsonMapper_Exception
	 */
	protected function getFormContent(): string{
		$output  = Html::input( 'hidden', 'action', 'depicter-lead-submit') . "\n";
		$output .= Html::input( 'hidden', '_sourceId', $this->getDocumentID() ) . "\n";

		if ( !empty( $this->options->captcha ) ) {
			$clientKey = \Depicter::options()->get('google_recaptcha_client_key', false);
			$secretKey = \Depicter::options()->get('google_recaptcha_secret_key', false);

			if ( $clientKey && $secretKey ) {
				$output .= Html::input( 'hidden', '_g_recaptcha_key'  , $clientKey ) . "\n";
				$output .= Html::input( 'hidden', '_g_recaptcha_token', '' ) . "\n";
			}
		}

		if ( $this->childrenObjects ) {
			$output .= Html::input( 'hidden', '_contentId', $this->getID() ) . "\n";
			$output .= Html::input( 'hidden', '_contentName', $this->getName() ) . "\n";
		}

		$output .= Html::input( 'hidden', '_csrfToken', wp_create_nonce( 'depicter-csrf-lead-' . $this->getDocumentID() ) ) . "\n\n";

		if ( ! empty( $this->childrenObjects ) ) {
			foreach ( $this->childrenObjects as $element ) {
				$output .= $element->prepare()->render();
			}
		}

		return $output;
	}

	protected function getInputContent(): string{
		$output = '';

		if( ! empty( $this->options->attributes->name ) ){
			$this->options->attributes->name = sanitize_title( $this->options->attributes->name );
		}
		if ( ! empty( $this->options->label ) && ! empty( $this->options->showLabel ) ) {
			$output .= Html::label([
				'class' => Selector::prefixify( 'field-label' ),
				'for' => $this->options->attributes->name
			], $this->options->label ) . "\n";
		}

		$args = [
			'id' => $this->options->attributes->name,
			'name' => $this->options->attributes->name,
			'placeholder' => $this->options->placeholder ?? '',
		];

		if ( ! empty( $this->options->attributes->autoComplete ) ) {
			$args['autocomplete'] = $this->options->attributes->autoComplete;
		}

		if ( ! empty( $this->options->attributes->required ) ) {
			$args['required'] = '';
		}

		switch ( $this->options->type ) {
			case 'textarea':
				$args['class'] = Selector::prefixify( 'input' ) . ' ' . Selector::prefixify( 'textarea' );
				if ( ! empty( $this->options->inputHeight ) ) {
					$args['style'] = 'height: ' . $this->options->inputHeight . 'px;';
				}
				$output .= Html::textarea( $args ) . "\n";
				break;
			case 'text':
			case 'email':
			case 'number':
			case 'tel':
			case 'url':
				$args['class'] = Selector::prefixify( 'input' ) . ' ' . Selector::prefixify( 'text-input' );
				$output .= Html::input( $this->options->type, $this->options->attributes->name, '', $args ) . "\n";
				break;
			case 'checkbox':
				$args['class'] = Selector::prefixify( 'input' ) . ' ' . Selector::prefixify( 'checkbox-input' );
				$output .= Html::input( $this->options->type, $this->options->attributes->name, '', $args ) . "\n";
				$output = Html::div([
					'class' => Selector::prefixify( 'choice-container' )
                ], $output ) . "\n";
				break;
			case 'submit':
			default:
				break;
		}

		if ( ! empty( $this->options->description ) && ! empty( $this->options->showDescription ) ) {
			$output .= Html::p([
                'class' => Selector::prefixify( 'field-description' )
            ], $this->options->description ) . "\n";
		}

		return $output;
	}

	protected function getMessageContent(): string{
		$output = '';
		if ( ! empty( $this->options->showSuccessMessage ) && ! empty( $this->options->successMessage ) ) {
			$output .= "\n" . Html::p([
				'class' => Selector::prefixify( 'message' ) . ' ' . Selector::prefixify( 'message-success' )
            ], $this->options->successMessage );
		}

		if ( ! empty( $this->options->errorMessage ) ) {
			$output .= "\n" . Html::p([
				'class' => Selector::prefixify( 'message' ) . ' ' . Selector::prefixify( 'message-error' ),
				'style' => ! empty( $this->options->errorTextColor ) ? 'color: ' . $this->options->errorTextColor . ';' : '',
            ], $this->options->errorMessage );
		}

		return $output;
	}

	/**
	 * Get list of selector and CSS for element and belonging child elements
	 *
	 * @return array
	 * @throws \JsonMapper_Exception
	 */
	public function getSelectorAndCssList(){
		parent::getSelectorAndCssList();
		foreach ( $this->childrenObjects as $element ) {
			$this->selectorCssList = Arr::merge( $this->selectorCssList, $element->prepare()->getSelectorAndCssList() );
		}

		$innerStyles = $this->prepare()->innerStyles;
		if ( !empty( $innerStyles ) ) {

			foreach( $innerStyles as $cssSelector => $styles ){
				if ( empty( $styles ) || ! $styles instanceof Styles ) {
					continue;
				}

				$generalCss = $innerStyles->{$cssSelector}->getGeneralCss('normal');
				$this->selectorCssList[ '.' . $this->getStyleSelector() . ' .' . $this->camelCaseToHyphenated( $cssSelector ) ] = $generalCss;
			}
		}

		return $this->selectorCssList;
	}

	/**
	 * change a string from camelCase style to hyphenated style
	 * @param $inputString
	 *
	 * @return string
	 */
	public function camelCaseToHyphenated( $inputString ) {
		return strtolower( preg_replace('/(?<!^)[A-Z]/', '-$0', $inputString ) );
	}

	/**
	 * Retrieves form submit path
	 *
	 * @return string
	 */
	protected function getSubmitPath(){
		return trim( wp_parse_url( self_admin_url( 'admin-ajax.php' ), PHP_URL_PATH ) );
	}
}
