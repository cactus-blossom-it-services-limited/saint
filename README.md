# saint
- A drupal 8 custom theme

### Installing in Drupal 8
1. Create a folder named 'custom' inside the themes folder if one is not there already.
2. Clone this repository inside it.
3. Enable the theme inside the 'Appearance' admin menu.
4. Clear the cache.

### Instructions
- The bootstrap 4 source files are installed using composer
- Run ``composer require twbs/bootstrap`` inside the theme root
- The bootstrap source files are inside the vendor/twbs/bootstrap directory
- Run ``gulp sass`` from inside the root directory to compile .scss files

Il est nécessaire que d'autres tâches soient ajoutées à gulp:
1. To watch for changes to the .scss files
2. To minify the .css files


