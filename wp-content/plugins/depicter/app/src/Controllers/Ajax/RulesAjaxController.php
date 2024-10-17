<?php

namespace Depicter\Controllers\Ajax;


use Averta\Core\Utility\Arr;
use Averta\WordPress\Utility\JSON;
use Depicter\Rules\Conditions\ListConditions;
use Depicter\Utility\Sanitize;
use WPEmerge\Requests\RequestInterface;

class RulesAjaxController {

	/**
	 * Store rules data
	 *
	 * @param RequestInterface $request
	 * @param                  $view
	 *
	 * @return \Psr\Http\Message\ResponseInterface
	 */
    public function store( RequestInterface $request, $view ) {
        $id = Sanitize::int( $request->body( 'ID', '' ) );
        $content = Sanitize::textfield( $request->body( 'content', '' ) );

        if ( empty( $content ) || empty( $id ) || ! JSON::isJson( $content ) ) {
            return \Depicter::json([
                'errors' => [ __( 'Both id and content are required.', 'depicter' ) ]
            ])->withStatus(400);
        }

		\Depicter::metaRepository()->update( $id, 'rules', $content );

		do_action( 'depicter/rules/after/store', $id, $content, $request );

	    return \Depicter::json( [ 'success' => true ] )->withStatus( 200 );
    }

	/**
	 * Show stored rules for provided document ID
	 *
	 * @param RequestInterface $request
	 * @param                  $view
	 *
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	public function show( RequestInterface $request, $view ) {
		$id = Sanitize::int( $request->query( 'ID', '' ) );

		if ( empty( $id ) ) {
			return \Depicter::json([
				'errors' => [ __( 'Document id is required.', 'depicter' ) ]
			])->withStatus(400);
		}

		$result = [
			'displayRules' => \Depicter::document()->displayRules( $id )->get(),
			'conditions'   => \Depicter::conditions()->toArray( true )
		];

		return \Depicter::json( $result )->withStatus( 200 );
	}

	public function all( RequestInterface $request, $view ) {
//      TODO - check following samples

//		$all = \Depicter::conditions()->all(); // get all defined conditions instances
//		$all = \Depicter::conditions()->toArray(); // get all defined conditions in array
//		$all = \Depicter::conditions()->toArray( true ); // get all defined conditions in groups
//		$all = \Depicter::conditions()->find('WordPress_Post')->getQueryResults(); // get query results for a condition
//		$all = \Depicter::conditions()->find('WordPress_Page')->getQueryResults(); // get query results for a condition

//		$all = \Depicter::document()->displayRules(18)->raw();      // get raw rules for a document by ID
//		$all = \Depicter::document()->displayRules(18)->toArray(); // get rules in array for a document by ID
//		$all = \Depicter::document()->displayRules(18)->get(); // get rules in object format for a document by ID

//		$all = \Depicter::document()->displayRules(18)->displayConditions; // get displayConditions for a document by ID in array format
//		$all = \Depicter::document()->displayRules(18)->displayConditionsSlim; // get displayConditions for a document by ID without redundant group and condition IDs

//		$all = \Depicter::document()->displayRules(18)->displayConditions()->all(); // get all instances of displayConditions for a document
//		$all = \Depicter::document()->displayRules(18)->displayConditions()->groups(); // get all instances of displayConditionGroups for a document
//		$all = \Depicter::document()->displayRules(18)->displayConditions()->areMet(); // check if displayConditions for a document are met or not

		$all = \Depicter::document()->getConditionalDocumentIDs();

		return \Depicter::json( $all )->withStatus( 200 );
	}

	/**
	 * List condition values for given query
	 *
	 * @param RequestInterface $request
	 * @param                  $view
	 *
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	public function conditionValues( RequestInterface $request, $view ) {
		$query = Sanitize::textfield( $request->query( 'query', '' ) );

		if ( empty( $query ) ) {
			return \Depicter::json([
				'errors' => [ __( 'query is required.', 'depicter' ) ]
			])->withStatus(400);
		}

		try{
			// return \Depicter::json( \Depicter::conditionsManager()->getConditionOptions( $query ) )->withStatus(200);
			return \Depicter::json( \Depicter::conditions()->find( $query )->getQueryResults() )->withStatus(200);
		} catch( \Exception $exception ) {
			return \Depicter::json([
				'errors' => [ $exception->getMessage() ]
			])->withStatus(400);
		}
	}
}
