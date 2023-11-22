const path = require( 'path' );

const defaultConfig = require( '@wordpress/scripts/config/webpack.config.js' );

module.exports = {
	...defaultConfig,
	entry: {
		search: path.resolve( __dirname, 'src', 'search.js' ),
		settings: path.resolve( __dirname, 'src', 'settings.js' ),
	},
};
