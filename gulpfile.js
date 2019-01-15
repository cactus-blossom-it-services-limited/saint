// Load Node Modules/Plugins
var gulp = require('gulp');
var sass = require('gulp-sass');

/* After receiving an error message i found this solution:
 https://stackoverflow.com/questions/36897877/gulp-error-the-following-tasks-did-not-complete-did-you-forget-to-signal-async
 And i implemented 3. Call the callback function. It has cleared the error message */
gulp.task('sass', function (done) {
    gulp.src('./sass/*.scss')
        .pipe(sass())
        .pipe(gulp.dest('./css'));
        done();
});

// Instructions for use:
// At the command line type 'gulp sass' to run the above sass task
// That assumes you are running it from inside the same folder as the gulpfile.js

