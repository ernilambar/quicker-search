( function( $ ) {
	$( document ).ready(function($){
		var post_api_url = '';

		if ( '' != quickerSearchSettings.post_type ) {
			if ( 'post' == quickerSearchSettings.post_type  ) {
				post_api_url = quickerSearchSettings.home_url + '/wp-json/wp/v2/posts';
			}
			else if ( 'page' == quickerSearchSettings.post_type ) {
				post_api_url = quickerSearchSettings.home_url + '/wp-json/wp/v2/pages';
			}
			else {
				post_api_url = quickerSearchSettings.home_url + '/wp-json/wp/v2/' + quickerSearchSettings.post_type;
			}
		}

		if ( '' != post_api_url ) {
			$( "#post-search-input" ).autocomplete({
				source: function( request, response ) {
					$.ajax({
						url: post_api_url,
						data: {
	                        search: request.term
						},
						success: function( data ) {
							response($.map(data, function(item) {
								return {
									label: item.title.rendered,
									id: item.id
								};
							}));
						}
					});
				},
				minLength: 2,
				select: function( event, ui ) {
	                window.location.href = quickerSearchSettings.admin_url + 'post.php?post=' + ui.item.id + '&action=edit';
				}
			}); // End #post-search-input.
		} // End if not empty URL.
	});
} )( jQuery );
