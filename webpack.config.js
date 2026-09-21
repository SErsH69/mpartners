require('dotenv').config()
const BrowserSyncPlugin = require('browser-sync-webpack-plugin')
const path = require('path')
const MiniCssExtractPlugin = require('mini-css-extract-plugin')
const CopyPlugin = require('copy-webpack-plugin')
const { WebpackManifestPlugin } = require('webpack-manifest-plugin')
const ImageMinimizerPlugin = require('image-minimizer-webpack-plugin')
const webpack = require('webpack')

const isProduction = process.env.NODE_ENV === 'production'
const isWindows = process.env.NODE_OS_ENV === 'win32'
const bsPort = process.env.BS_PORT
const bsOn = process.env.BS_ON === 'true'

const plugins = [
    new MiniCssExtractPlugin({
        filename: '[name].[contenthash].css',
    }),
    new CopyPlugin({
        patterns: [
            {
                from: 'assets/img',
                to: 'img',
                noErrorOnMissing: true,
                globOptions: {
                    ignore: ['**/.DS_Store'],
                },
            },
            {
                from: 'assets/fonts',
                to: 'fonts',
                noErrorOnMissing: true,
                globOptions: {
                    ignore: ['**/.DS_Store'],
                },
            },
        ],
    }),
    new WebpackManifestPlugin(),
    new ImageMinimizerPlugin({
        minimizer: {
            implementation: ImageMinimizerPlugin.imageminGenerate,
            options: {
                plugins: [
                    ['gifsicle', { interlaced: true }],
                    ['mozjpeg', { progressive: true, quality: 75 }],
                    ['pngquant', { quality: [0.6, 0.8] }],
                    [
                        'svgo',
                        {
                            plugins: [
                                {
                                    name: 'removeViewBox',
                                    active: false,
                                },
                            ],
                        },
                    ],
                ],
            },
        },
    }),
    new webpack.DefinePlugin({
        BS_ON: JSON.stringify(process.env.BS_ON),
        BS_PORT: JSON.stringify(process.env.BS_PORT),
        NODE_ENV: JSON.stringify(process.env.NODE_ENV),
    }),
]

if (bsOn) {
    plugins.push(
        new BrowserSyncPlugin(
            {
                proxy: 'http://wordpress',
                files: [
                    './wp-content/themes/**/**/*.php',
                    './wp-content/themes/**/**/*.css',
                    './wp-content/themes/**/**/*.js',
                ],
                reloadDelay: 0,
                open: false,
                port: bsPort,
            },
            {
                reload: true,
            },
        ),
    )
}

module.exports = {
    devtool: isProduction ? 'source-map' : 'inline-source-map',
    cache: {
        type: 'memory',
    },
    watchOptions: {
        poll: isWindows ? 1000 : false,
    },
    entry: {
        main: './assets/js/entry/mainEntry.js',
        'spacer-block': './assets/js/entry/spacerEntry.js',
    },
    output: {
        filename: '[name].[contenthash].js',
        path: path.resolve(__dirname, 'theme/dist'),
    },
    module: {
        rules: [
            {
                test: /\.css$/,
                include: [path.resolve(__dirname, 'assets')],
                use: [
                    MiniCssExtractPlugin.loader,
                    {
                        loader: 'css-loader',
                        options: {
                            // Шрифты и картинки копирует CopyPlugin, пути в CSS
                            // уже указаны относительно theme/dist/main.css.
                            url: false,
                            sourceMap: !isProduction,
                        },
                    },
                    {
                        loader: 'postcss-loader',
                        options: {
                            postcssOptions: {
                                plugins: ['autoprefixer', 'cssnano'],
                            },
                            sourceMap: !isProduction,
                        },
                    },
                ],
            },
            {
                test: /\.scss$/,
                include: [path.resolve(__dirname, 'assets')],
                use: [
                    MiniCssExtractPlugin.loader,
                    {
                        loader: 'css-loader',
                        options: {
                            // Шрифты и картинки копирует CopyPlugin, пути в CSS
                            // уже указаны относительно theme/dist/main.css.
                            url: false,
                            sourceMap: !isProduction,
                        },
                    },
                    {
                        loader: 'postcss-loader',
                        options: {
                            postcssOptions: {
                                plugins: ['autoprefixer', 'cssnano'],
                            },
                            sourceMap: !isProduction,
                        },
                    },
                    {
                        loader: 'sass-loader',
                        options: {
                            sourceMap: !isProduction,
                        },
                    },
                ],
            },
            {
                test: /\.[jt]sx?$/,
                include: [path.resolve(__dirname, 'assets')],
                loader: 'esbuild-loader',
                options: {
                    target: 'es2015',
                },
            },
        ],
    },
    plugins,
}
