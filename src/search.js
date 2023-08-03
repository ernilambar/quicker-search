import autoComplete from '@tarekraafat/autocomplete.js';

import './scss/search.scss';

const searchInput = document.getElementById( 'post-search-input' );

if ( searchInput ) {
	new autoComplete( {
		selector: '#post-search-input',
		placeHolder: 'Search keyword...',
		threshold: 3,
		debounce: 100,
		data: {
			src: async ( query ) => {
				try {
					const formData = new FormData();
					formData.append( 'action', 'qs_get_posts' );
					formData.append( 'keyword', query );
					formData.append( 'post_type', QUICKER_SEARCH.post_type );

					const source = await fetch( QUICKER_SEARCH.ajax_url, {
						method: 'POST',
						credentials: 'same-origin',
						body: formData,
					} );

					const data = await source.json();

					const posts = [];

					data.forEach( ( item ) => {
						posts.push( {
							id: item.id,
							title: item.title,
						} );
					} );

					return posts;
				} catch ( error ) {
					return error;
				}
			},
			keys: [ 'title' ],
		},
		resultItem: {
			tag: 'li',
			class: 'autoComplete_result',
			element: ( item, data ) => {
				const url =
					QUICKER_SEARCH.admin_url + 'post.php?post=' + data.value.id + '&action=edit';
				item.innerHTML = `<a href='${ url }'>${ data.value.title }</a>`;
			},
		},
	} );
}
