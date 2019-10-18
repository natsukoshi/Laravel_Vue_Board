const mix = require('laravel-mix');

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
//ファイルが更新されたときに自動的にブラウザがリロードされる
mix.browserSync('192.168.33.10')
    // JSとVueコンポーネントをコンパイルする。コンパイル対象と結果の配置先
    .js('resources/js/app.js', 'public/js')
    //Sass・Scssのコンパイル対象と結果の配置先
    .sass('resources/sass/app.scss', 'public/css')
    // コンパイルしたファイルのバージョニングが有効になる
    // キャッシュでファイル更新が反映されないの防ぐ
    .version();
//デバッグ用にソースマップを出力する



if (!mix.inProduction()) {
    mix.webpackConfig({
        devtool: 'source-map'
    }).sourceMaps();
}
