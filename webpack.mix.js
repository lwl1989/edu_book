let mix = require('laravel-mix');

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
// mix.webpackConfig({
//     module: {
//         rules: [
//             {
//                 test: /\.js$/,
//                 exclude: /node_modules/,
//                 loader: 'babel-loader',
//                 options: {
//                     "plugins": [["component", [
//                         {
//                             "libraryName": "element-ui",
//                             "styleLibraryName": "theme-chalk"
//                         }
//                     ]]]
//                 }
//             },
//             // {
//             //     test: /\.css$/,
//             //     use: 'css-loader'
//             // },
//             // {
//             //     test: /\.css$/,
//             //     loader: "style-loader!css-loader"
//             // },
//         ]
//     },
//     // plugins:[
//     //     new ExtractTextPlugin('styles.css')
//     // ]
// });

mix
    .js('resources/assets/js/admin.js', 'public/js')
    .js('resources/assets/js/auth.js', 'public/js')
    .extract(['element-ui'],'public/js/element-ui.js')
    .extract(['vue','vue-router','vue-resource'],'public/js/vue.js')
    .autoload({
        vue: ['Vue', 'window.Vue']
    })
    //.copy('node_modules/element-ui/lib/theme-chalk/index.css', 'public/css/admin.css')
    .sass('resources/assets/sass/admin.scss', 'public/css/admin.css')

;
//
// console.log(mix.config);
// return;