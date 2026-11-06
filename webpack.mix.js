let mix = require("laravel-mix");
require("laravel-mix-purgecss");
require("dotenv").config();
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling f all the JS files.
 |
 */
// 'prod' por defecto: compila app.scss con purgecss y versiona los assets.
const app = process.env.MIX_APP || "prod";

// Uglify por defecto renombra los identificadores (mangle). Con este bundle
// eso rompe componentes de Vue en tiempo de ejecucion ("r is not a function"
// dentro de __patch__). Se mantiene la compresion y se desactiva el renombrado.
mix.options({
  uglify: {
    uglifyOptions: {
      // compress elimina codigo que Vue necesita en tiempo de ejecucion
      // (genera "n is not a function" dentro de __patch__). Se apaga junto
      // con mangle: el bundle queda sin transformaciones.
      compress: false,
      mangle: false,
    },
  },
});
mix

  .webpackConfig({ devtool: "inline-source-map" })
  .sourceMaps()
  .js("resources/assets/js/app.js", "public/js")
  .js("resources/assets/js/datatable.js", "public/js")
  .sass("resources/assets/sass/wkhtml.scss", "public/css")
  .sass("resources/assets/sass/materialicons.scss", "public/css")
  .sass("resources/assets/sass/datatable.scss", "public/css")
  .stylus('resources/assets/stylus/vuetify.styl', 'public/css')
switch (app) {
  case 'prod':
    mix
    .sass("resources/assets/sass/app.scss", "public/css")
    .version()
    .purgeCss({
      enabled: true,

      globs: [
        path.join(__dirname, "resources/views/**/*.blade.php"),
        path.join(__dirname, "resources/assets/js/**/*.vue")
      ],
      extensions: ["html", "js", "php", "vue"],
      // 'mdi', 'fa' y 'glyphicon' son la clase base del icono (sin guion):
      // sin ellas el <i> no tiene font-family y el icono sale como texto.
      whitelist: ['mdi', 'fa', 'glyphicon', 'text-base'],
      whitelistPatterns: [/language/, /hljs/,/chosen-select/,/chosen-/, /^mdi-/, /^fa-/, /^glyphicon-/]
    })
    ;
   break;

  case 'test':
    mix
    .sass("resources/assets/sass/app_test.scss", "public/css/app.css")
    .version()
    .purgeCss({
      enabled: true,

      globs: [
        path.join(__dirname, "resources/views/**/*.blade.php"),
        path.join(__dirname, "resources/assets/js/**/*.vue")
      ],
      extensions: ["html", "js", "php", "vue"],
      // 'mdi', 'fa' y 'glyphicon' son la clase base del icono (sin guion):
      // sin ellas el <i> no tiene font-family y el icono sale como texto.
      whitelist: ['mdi', 'fa', 'glyphicon', 'text-base'],
      whitelistPatterns: [/language/, /hljs/,/chosen-select/,/chosen-/, /^mdi-/, /^fa-/, /^glyphicon-/]
    });
   break;
  case 'dev':
    mix
    .sass("resources/assets/sass/app_dev.scss", "public/css/app.css")
   break;
  default:
    break;
}

  // mix.browserSync("https://wwwachuchus.dev");
