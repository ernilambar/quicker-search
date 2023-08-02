import autoComplete from '@tarekraafat/autocomplete.js';

import './scss/search.scss';

const searchInput = document.getElementById( 'post-search-input' );

if ( searchInput ) {
	let postApiUrl = '';

	if ( '' !== quickerSearchSettings.post_type ) {
		if ( 'post' === quickerSearchSettings.post_type ) {
			postApiUrl = quickerSearchSettings.rest_url + 'wp/v2/posts';
		} else if ( 'page' === quickerSearchSettings.post_type ) {
			postApiUrl = quickerSearchSettings.rest_url + 'wp/v2/pages';
		} else {
			postApiUrl =
				quickerSearchSettings.rest_url +
				'/wp/v2/' +
				quickerSearchSettings.post_type;
		}
	}

	new autoComplete( {
		selector: '#post-search-input',
		placeHolder: 'Search keyword...',
		threshold: 3,
		debounce: 300,
		data: {
			src: async ( query ) => {
				try {
					const source = await fetch(
						postApiUrl + '?search=' + query
					);

					const data = await source.json();

					const posts = [];

					data.forEach( ( item ) => {
						posts.push( {
							id: item.id,
							title: item.title.rendered,
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
					quickerSearchSettings.admin_url +
					'post.php?post=' +
					data.value.id +
					'&action=edit';
				item.innerHTML = `<a href='${ url }'>${ data.value.title }</a>`;
			},
		},
	} );
}
