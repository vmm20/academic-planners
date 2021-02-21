# Academic Planners
 Generate academic planners using PHP.  In elementary school, I used to sell academic planners that looked just like this, except I made them in Excel.  Years later, long after I stopped selling them, I realized I could make them far more versatile by using HTML and the power of a general purpose scripting language like PHP.  This is still a work in progress, and I don't actually intend to use it for anything anymore, but I thought I would publish this project anyway.
 
 [Sass](https://github.com/sass/dart-sass/releases) is required for the stylesheets, and PHP is required to run the development server locally.
 
 Run `sass css/planners.scss css/planners.css` to generate the CSS file.  Make sure that `sass` is on your PATH after you download it.  You can also use the `--watch` flag to regenerate the CSS file in real time when the SCSS file is modified.
 
 Run `php -t . -S localhost:8080` in the repository's root directory to start the development server.  Then, open a browser and visit `http://localhost:8080/planners.php` to see the results.
 
 Add `start` and `end` URL parameters with eight-digit dates to adjust the start and end dates.  By default, the start date is today, and the end date is situated such that one week is output.  For example, ```http://localhost:8080/planners.php?start=20210101&end=20211231``` would generate a series of pages for all of 2021.
 
 To see the printable version, either view the print preview in the browser, or open DevTools and find the Rendering tab under more tools, which will have an option to override the CSS media type.
