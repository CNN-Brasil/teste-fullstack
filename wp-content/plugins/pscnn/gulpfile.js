'use-strict';

const gulp = require('gulp');
const gulpSass = require('gulp-sass');
const sass = gulpSass(require('sass'));
const babel = require('gulp-babel');
const uglify = require('gulp-uglify');
const concat = require('gulp-concat');
const rename = require('gulp-rename');

sass.compiler = require('node-sass');

gulp.task('build-styles', () => {
    return gulp.src('assets-src/styles/main.scss')
        .pipe(sass({
            outputStyle: 'compressed',
        }))
        .pipe(rename('main.min.css'))
        .pipe(gulp.dest('assets/styles'));
});

gulp.task('build-scripts', () => {
    return gulp.src(['assets-src/scripts/components/*.js', 'assets-src/scripts/main.js'])
        .pipe(concat('main.min.js'))
        .pipe(babel({
            presets: ['@babel/env'],
        }))
        .pipe(uglify())
        .pipe(gulp.dest('assets/scripts'));
});

gulp.task('default', gulp.series('build-styles', 'build-scripts'));
