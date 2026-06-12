const mix = require('laravel-mix');
const path = require('path');
const fixCss = require('./build-fix');

mix.webpackConfig({
    resolve: {
        alias: {
            "@": path.resolve(__dirname, "resources/js"),
            "@css": path.resolve(__dirname, "resources/css")
        },
    },
    stats: {
        children: false,
    },
    plugins: [
        {
            apply(compiler) {
                compiler.hooks.done.tap('FixCssPlugin', () => {
                    fixCss();
                });
            }
        }
    ]
})

mix.js('resources/js/app.js', 'js/app.js').version()
    .vue({ version: 3 })
    .postCss('resources/css/app.css', 'css/app.css', [
        require('@tailwindcss/postcss'),
        require("autoprefixer"),
    ]).version();