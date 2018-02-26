let mix = require("laravel-mix");
require("laravel-mix-purgecss");
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
  .js("resources/assets/js/app.js", "public/js")
  .sass("resources/assets/sass/wkhtml.scss", "public/css")
  .sass("resources/assets/sass/materialicons.scss", "public/css")
  .sass("resources/assets/sass/app.scss", "public/css")
  .purgeCss({
    enabled: true,

    globs: [
      path.join(__dirname, "resources/views/**/*.blade.php"),
      path.join(__dirname, "resources/assets/js/**/*.vue")
    ],
    extensions: ["html", "js", "php", "vue"],
    whitelistPatterns: [/language/, /hljs/]
  });
