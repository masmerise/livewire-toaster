const mix = require('laravel-mix')

mix.js('resources/js/index.js', 'js/toaster.js').setPublicPath('dist')
