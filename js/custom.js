( function( $ ) {

	$( document ).ready(function($){

		var post_api_url = '';
		if ( '' != Quicker_Search_Settings.post_type ) {
			if ( 'post' == Quicker_Search_Settings.post_type  ) {
				post_api_url = Quicker_Search_Settings.home_url + '/wp-json/wp/v2/posts';
			}
			else if ( 'page' == Quicker_Search_Settings.post_type ) {
				post_api_url = Quicker_Search_Settings.home_url + '/wp-json/wp/v2/pages';
			}
			else {
				post_api_url = Quicker_Search_Settings.home_url + '/wp-json/wp/v2/' + Quicker_Search_Settings.post_type;
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
	                window.location.href = Quicker_Search_Settings.admin_url + 'post.php?post=' + ui.item.id + '&action=edit';
				}
			}); // End #post-search-input.
		} // End if not empty URL.

	});

} )( jQuery );
