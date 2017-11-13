# phpAPE [![N|Solid](https://i.imgur.com/F7E6wDd.png)](https://ape.compsci.ewu.edu/phpAPE/)

phpAPE is a web application that allows for the administration, registration, and grading of exams.
  - Create exams and in-class exams
  - Create exam categories with custom points
  - Manage locations, rooms, and seats
  - Allow students to register for exams during certain time periods
  - Assign graders to grade exams
  - Register entire classes for an exam with a csv file
  - Configure custom reports and generate reports for exams

## Requirements

| Software | Version |
| ------ | ------ |
| php | 7.0.22 |
| apache | 2.4.18 |
| curl | 7.47.0 |

## Installation

--Step 1. Install the required bower packages:
```
bower install
```
--Step 2. Install the required php dependencies:
```
composer install
```
--Step 3. Install the required node modules:
```
npm install
```
--Step 4. Move datetimepicker to the vendor directory: 
```
mv node_modules/jquery-datetimepicker vendor
```

--Step 5. Edit the `config.default.php` file and set the CONFIG_PATH to equal the absolute path to the "config" directory of this project.

--Step 6. Edit all of the default files located in the "config" directory.

--Step 7. For each file in the "config" directory, remove "-default" from the file name.

--Step 8. Rename `config.default.php` to `config.php`

--Step 9. Create a `cache` directory that is owned by the web server:
```
mkdir cache; chmod 755 cache; chown www-data cache
```

--Step 10. Create a `security.log` file located in the LOG_PATH as defined in `config/path.config.php`
```
cd /var/www; touch security.log; 
chown www-data security.log; chmod 755 security.log
```

**IMPORTANT: Make sure that `DEBUG` is set to `false` in `config/security.config.php`**

## Directory Structure

```
./ ------------------------------------------ Root directory
├── api ------------------------------------- Contains API files
├── cache ----------------------------------- Twig caching directory
├── config ---------------------------------- Configuration files
├── includes -------------------------------- PHP backend inclusions
│   ├── db ---------------------------------- Database related inclusions
│   │   ├── functions ----------------------- Database page functions
│   │   └── queries ------------------------- Database query functions
│   └── operations -------------------------- Operation behaviors for the API
├── node_modules ---------------------------- Contains installed node packages
├── scripts --------------------------------- APE javascript files
├── sources --------------------------------- General resources
│   ├── images ------------------------------ Image resources
│   └── styles ------------------------------ Styling resources
├── templates ------------------------------- Twig templates
│   ├── components -------------------------- Markup+JS that use Operations
│   ├── layout ------------------------------ Common templates that compose layout
│   ├── modals ------------------------------ Modal related templates
│   └── pages ------------------------------- Page templates that include Components
└── vendor ---------------------------------- Contains third party libraries
    ├── bootstrap --------------------------- CSS library
    ├── composer ---------------------------- PHP Dependency Manager (Twig)
    ├── jquery ------------------------------ JavaScript library
    ├── jquery-datetimepicker --------------- jQuery plugin
    ├── jquery-mousewheel ------------------- jQuery plugin
    ├── less -------------------------------- CSS pre-processor
    ├── lodash ------------------------------ Utility function JavaScript library
    ├── phpcas ------------------------------ CAS authentication dependency
    │   └── CAS
    ├── php-date-formatter ------------------ jQuery plugin
    ├── remarkable-bootstrap-notify --------- jQuery plugin
    ├── symfony ----------------------------- Twig dependency
    ├── tether ------------------------------ Bootstrap dependency
    └── twig -------------------------------- Template engine

```