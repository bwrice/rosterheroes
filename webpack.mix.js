const mix = require('laravel-mix');

mix.js('resources/js/createSquad.js', 'public/js').version();
mix.js('resources/js/commandCenter.js', 'public/js').version();

mix.stylus('resources/stylus/app.styl', 'public/css').version();
