const mix = require('laravel-mix');

mix.js('resources/js/createSquad.js', 'public/js').version();
mix.js('resources/js/commandCenter.js', 'public/js').version();

// mix.sass('resources/sass/commandCenter.scss', 'public/css').version();
