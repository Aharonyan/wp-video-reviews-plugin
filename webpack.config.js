
const defaultConfig = require( '@wordpress/scripts/config/webpack.config.js' );
const path = require( 'path' );

module.exports = {
	...defaultConfig,
	mode: 'production',
	entry: {
		front: path.resolve( process.cwd(), 'src', 'front.js' ),
        admin: path.resolve( process.cwd(), 'src', 'admin.js' ),
		frontStyle: path.resolve( process.cwd(), 'src', 'front.scss' ),
		adminStyle: path.resolve( process.cwd(), 'src', 'admin.scss' ),
	}
};