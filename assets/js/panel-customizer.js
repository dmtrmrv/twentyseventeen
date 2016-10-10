/**
 * Theme Customizer enhancements, specific to panels, for a better user experience.
 *
 * This allows us to detect when the user has opened specific sections within the Customizer,
 * and adjust our preview pane accordingly.
 */

( function( $ ) {

	wp.customize.bind( 'ready', function() {

		var twentyseventeenPanelCountMax = parseInt( twentyseventeenPanelCountMax[0] );

		for ( var panel = 1; panel <= twentyseventeenPanelCountMax; panel++ ) {
			console.log( panel );
			// Detect when the section for each panel is expanded (or closed) so we can adjust preview accordingly
			wp.customize.section( 'panel_' + panel.toString() ).expanded.bind( function( isExpanding ) {
				// isExpanding will = true if you're entering the section, false if you're leaving it
				wp.customize.previewer.send( 'section-highlight', { section: 'twentyseventeen-panel' + panel.toString(), expanded: isExpanding } );
			} );
		}
	} );
} )( jQuery );
