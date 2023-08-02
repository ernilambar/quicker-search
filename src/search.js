import "./scss/search.scss";

import $ from 'jquery';

( function ( $ ) {
	$( document ).ready( function ( $ ) {
		let post_api_url = '';

		if ( '' !== quickerSearchSettings.post_type ) {
			if ( 'post' === quickerSearchSettings.post_type ) {
				post_api_url =
					quickerSearchSettings.home_url + '/wp-json/wp/v2/posts';
			} else if ( 'page' === quickerSearchSettings.post_type ) {
				post_api_url =
					quickerSearchSettings.home_url + '/wp-json/wp/v2/pages';
			} else {
				post_api_url =
					quickerSearchSettings.home_url +
					'/wp-json/wp/v2/' +
					quickerSearchSettings.post_type;
			}
		}

		if ( '' !== post_api_url ) {
			$( '#post-search-input' ).autocomplete( {
				source( request, response ) {
					$.ajax( {
						url: post_api_url,
						data: {
							search: request.term,
						},
						success( data ) {
							response(
								$.map( data, function ( item ) {
									return {
										label: item.title.rendered,
										id: item.id,
									};
								} )
							);
						},
					} );
				},
				minLength: 2,
				select( event, ui ) {
					window.location.href =
						quickerSearchSettings.admin_url +
						'post.php?post=' +
						ui.item.id +
						'&action=edit';
				},
			} );
		}
	} );
} )( jQuery );
