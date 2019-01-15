# saint
- A drupal 8 custom theme

### Installing in Drupal 8
1. Create a folder named 'custom' inside the themes folder if one is not there already.
2. Clone this repository inside it.
3. Enable the theme inside the 'Appearance' admin menu.
4. Clear the cache.

### Instructions
- The bootstrap 4 source files are installed using composer
- Find them inside the vendor/twbs/bootstrap directory
- Run ``gulp sass`` from inside the root directory to compile .scss files

 Tasks need to be written inside the gulpfile.js to watch for changes to .scss files
 and to minify .css files


