const path = require('path');
/**
 * @link https://github.com/webpack-contrib/css-minimizer-webpack-plugin
 */
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const CssMinimizerPlugin = require("css-minimizer-webpack-plugin");

module.exports = {
    entry: [
        './src/Resources/js/app.js',
        './src/Resources/scss/app.scss'
    ],
    output: {
        filename: 'app.js',
        path: path.resolve(__dirname, 'dist')
    },
    module: {
        rules: [
            {
            test: /.s?css$/,
            use: [MiniCssExtractPlugin.loader, "css-loader", "sass-loader"],
            },
        ],
    },
    optimization: {
        minimizer: [
            new CssMinimizerPlugin(),
        ],
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: 'app.css',
        })
    ],
    
};