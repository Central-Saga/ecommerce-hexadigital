const { src, dest, watch, series } = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const cleanCSS = require('gulp-clean-css');
const rename = require('gulp-rename');

// Tugas untuk mengompilasi SCSS ke style.min.css
function compileSass() {
    return src('public/assets/scss/index.scss')
        .pipe(sass({ quietDeps: true }).on('error', sass.logError))
        .pipe(cleanCSS())
        .pipe(rename('style.min.css'))
        .pipe(dest('public/assets/css'));
}

// Tugas untuk memantau perubahan pada file SCSS
function watchSass() {
    watch('public/assets/scss/**/*.scss', compileSass);
}

// Ekspor tugas default
exports.default = series(compileSass, watchSass);
