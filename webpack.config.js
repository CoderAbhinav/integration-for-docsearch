const path = require( 'path' );
const CssMinimizerPlugin = require( 'css-minimizer-webpack-plugin' );
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );

const sharedConfig = {
	...defaultConfig,
	output: {
		path: path.resolve( process.cwd(), 'assets', 'build', 'scripts' ),
		filename: '[name].min.js',
		chunkFilename: '[name].min.js',
	},
	plugins: [
		...defaultConfig.plugins,
	],
}

const frontendJS = {
	...sharedConfig,
	entry: {
		frontend: path.resolve( 'assets', 'src', 'js', 'frontend.js' )
	}
}

module.exports = [
	frontendJS
]
