const btnSelectAll = document.getElementById( 'qs-btn-select-all' );

if ( btnSelectAll ) {
	btnSelectAll.addEventListener( 'click', function( e ) {
		e.preventDefault();

		const parentField = document.querySelector( '.form-field-post_types' );

		const checkboxes = parentField.querySelectorAll( 'input[type=checkbox]' );

		checkboxes.forEach( ( cb ) => {
			cb.checked = true;
		} );
	} );
}

const btnSelectNone = document.getElementById( 'qs-btn-select-none' );

if ( btnSelectNone ) {
	btnSelectNone.addEventListener( 'click', function( e ) {
		e.preventDefault();

		const parentField = document.querySelector( '.form-field-post_types' );

		const checkboxes = parentField.querySelectorAll( 'input[type=checkbox]' );

		checkboxes.forEach( ( cb ) => {
			cb.checked = false;
		} );
	} );
}
