'use strict';

const path = require("path");
const {VueLoaderPlugin} = require("vue-loader");
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');
const sortCSSmq = require('sort-css-media-queries');
const CompileTimePlugin = require('webpack-compile-time-plugin');


const resourcesPath = "wp-content/themes/template/resources";

module.exports = {
    name: 'default',
    mode: 'development',
    entry: {
        app: `./${resourcesPath}/Private/Vue/app.js`,
        critical: `./${resourcesPath}/Private/Js/critical.js`,
    },
    output: {
        filename: '[name].js',
        path: path.resolve(__dirname, `${resourcesPath}/Public/Js`)
    },
    resolve: {
        alias: {
            'vue$': 'vue/dist/vue.esm.js'
        },
    },
    module: {
        rules: [
            {
                test: /\.vue$/,
                use: 'vue-loader',
            },
            {
                test: /\.js$/,
                loader: 'babel-loader',
                exclude: /node_modules/,
            },
            {
                test: /\.svg$/,
                use: [
                    {
                        loader: 'babel-loader',
                    },
                    {
                        loader: 'vue-svg-loader',
                        options: {
                            svgo: {
                                plugins: [{removeDimensions: false}, {removeViewBox: false}]
                            }
                        }
                    }
                ],
            },
            {
                test: /\.scss$/,
                use: [
                    {
                        //Load SCSS files from entry
                        loader: 'file-loader',
                        options: {
                            name: '../Css/[name].min.css',
                        }
                    },
                    {
                        loader: 'postcss-loader',
                        options: {
                            plugins: [
                                // Add css prefixes
                                require('autoprefixer'),
                                // Group same media queries
                                require("css-mqpacker")({
                                    sort: sortCSSmq
                                }),
                                // Minify CSS
                                require('cssnano'),
                            ]
                        }
                    },
                    {
                        // Compile SCSS to CSS
                        loader: 'sass-loader'
                    },

                ]
            },
        ]
    },
    plugins: [
        new VueLoaderPlugin(),
        new UglifyJsPlugin(),
        new CompileTimePlugin()
    ],
    watch: true,
    watchOptions: {
        ignored: /node_modules/
    }
};
