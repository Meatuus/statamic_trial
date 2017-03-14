/*
|--------------------------------------------------------------------------
| Set path variables
|--------------------------------------------------------------------------
*/

var public_assets_path = './';
var sass_path = 'sass/';
var js_path = 'js/';
var templates_path = 'templates/';

/*
|--------------------------------------------------------------------------
| Include dependencies
|--------------------------------------------------------------------------
*/

var autoprefixer = require('gulp-autoprefixer');
var globbing = require('gulp-css-globbing');
var gulp = require('gulp')
var include = require('gulp-include');
var livereload = require('gulp-livereload');
var modernizr = require('gulp-modernizr');
var notify = require('gulp-notify');
var plumber = require('gulp-plumber');
var rename = require('gulp-rename');
var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');
var uglify = require('gulp-uglify');

// Image compression
// var imagemin = require('gulp-imagemin');
// var pngquant = require('imagemin-pngquant');
// var tinypng = require('gulp-tinypng');

/*
|--------------------------------------------------------------------------
| Convert SASS to CSS
|--------------------------------------------------------------------------
*/

gulp.task('css', function () {
  var onError = function(err) {
    notify.onError({
      title:    "Gulp",
      subtitle: "Failure!",
      message:  "Error: <%= error.message %>"
    })(err);
    this.emit('end');
  };
  return gulp.src([
    sass_path + 'main.scss'
  ])
  .pipe(plumber({errorHandler: onError}))
  .pipe(globbing({
    extensions: ['.scss']
  }))
  .pipe(sourcemaps.init())
  .pipe(sass({
    outputStyle: 'compressed',
    errLogToConsole: false
  }))
  .pipe(autoprefixer())
  .pipe(sourcemaps.write("./"))
  .pipe(gulp.dest(public_assets_path + 'css'))
  .pipe(livereload({ auto: false }))
  .pipe(notify({
    title: 'Gulp',
    subtitle: 'Success',
    message: 'Compiled CSS (<%=file.relative%>)' }));
});

/*
|--------------------------------------------------------------------------
| Minify JavaScript
|--------------------------------------------------------------------------
*/

gulp.task('js', function() {
  return gulp.src(js_path + 'imports.js')
  .pipe(plumber())
  .pipe(include())
  .pipe(uglify())
  .pipe(rename('main.js'))
  .pipe(gulp.dest(js_path))
  .pipe(notify({ message: 'Minified JS (<%=file.relative%>)' }));
});

/*
|--------------------------------------------------------------------------
| Optimize Images
|--------------------------------------------------------------------------
*/

// gulp.task('images', function () {
//   return gulp.src(public_assets_path + 'img/*')
//   .pipe(imagemin({
//     progressive: true,
//     svgoPlugins: [{removeViewBox: false}],
//     use: [pngquant()]
//   }))
//   .pipe(gulp.dest(public_assets_path + 'img/'));
// });

// gulp.task('tinypng', function () {
//     gulp.src(public_assets_path + 'img/*.png')
//         .pipe(tinypng('UMKBV8s6QuwMtQudUF1BAeejyAQEip4N'))
//         .pipe(gulp.dest(public_assets_path + 'img/'));
// });

/*
|--------------------------------------------------------------------------
| Setup LiveReload and watcher
|--------------------------------------------------------------------------
*/

gulp.task('default', function ()
{
  // Create LiveReload server
  var server = livereload();
  livereload.listen();

  // Watch .scss files
  gulp.watch
  ([
   sass_path + '*.scss',
   sass_path + '**/*.scss'
   ],
   ['css']);

  // Watch main.js files
  gulp.watch
  ([
   js_path + 'components/*.js',
   js_path + 'imports.js'
   ],
   ['js']);

  // Watch view files
  gulp.watch
  ([
   templates_path + '**/*.html',
   templates_path + '*.html'
   ],
   function(file)
   {
     return gulp.src
     ([file.path])
     .pipe(livereload());
   });

  // Watch img files
  // gulp.watch
  // ([
  //  public_assets_path + 'img/*.*',
  //  public_assets_path + 'img/*/*.*'
  //  ],
  //  function(file)
  //  {
  //    return gulp.src
  //    ([file.path])
  //    .pipe(livereload());
  //  });
});
