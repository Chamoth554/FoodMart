( function( api ) {

	// Extends our custom "continental-restaurant" section.
	api.sectionConstructor['continental-restaurant'] = api.Section.extend( {

		// No events for this type of section.
		attachEvents: function () {},

		// Always make the section active.
		isContextuallyActive: function () {
			return true;
		}
	} );

} )( wp.customize );