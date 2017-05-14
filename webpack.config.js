const path = require('path');
const uglifyJSPlugin = require('uglifyjs-webpack-plugin');

module.exports = {
    entry: {
        main: "./app/Resources/web/js/main.js",
        login: "./app/Resources/web/js/login.js"
    },
    output: {
        filename: "[name].js",
        path: path.resolve(__dirname, 'web/assets/'),
        publicPath : '/assets/'
    },
    plugins: [
        new uglifyJSPlugin()
    ],
    module: {
        rules: [
            {
                test: /\.scss$/,
                use: [{
                    loader: "style-loader" // creates style nodes from JS strings
                }, {
                    loader: "css-loader" // translates CSS into CommonJS
                }, {
                    loader: "sass-loader" // compiles Sass to CSS
                }]
            },
            {
                test: /\.js$/,
                exclude: /(node_modules)/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: ['es2015']
                    }
                }
            }
        ]
    }
};
