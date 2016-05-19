jQuery( document ).ready( function() {
	jQuery( '.read-more-button' ).click( function( e ) {
		jQuery( '.read-more-button' ).hide();
		jQuery( '#read-more-hidden-copy' ).show( 'slow' );
		e.preventDefault();
	} );
} );
