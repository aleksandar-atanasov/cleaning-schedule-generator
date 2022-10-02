# cleaning-schedule-generator
Simple PHP Command Line application for generating cleaning schedule

# Intro
 - Clone this repo in the desired location, cd into the app folder and run `composer install`
 - In order to list all the commands available in the application run `./app list` in the command line.
 - To generate the cleaning schedule for the next 3 months by default run `./app generate:schedule`. 
 This commands also accepts optional option to override the default period of 3 months. In order to use it pass it as e.g `./app generate:schedule --period=5`. 
 This will generate a cleaning schedule for the next 5 months.


# Libraries Used:
"nesbot/carbon",
"league/csv"

# Symfony Components Used:
"symfony/console",
"symfony/config",
"symfony/dependency-injection",
"symfony/yaml",
