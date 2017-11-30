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
| mod-rewrite |  |
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

--**Step 11.** Enable mod-rewrite and restart apache:
```
a2enmod rewrite; service apache2 restart 
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
├── pages ----------------------------------- PHP page files that invoke renderTemplate(...)
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

## Adding a new page

Adding a new page consists of:
* Creating a new php file (pages/pageName.php)
    * Create a `$parameters` array to send extra variables to Twig template (can be an empty array)
    * Invoke `renderPage("pages/pageName.twig.html", $parameters);`
* Creating a new Twig file in `templates/pages`
    * Extend the base template `{% extends "layout/base.twig.html" %}`
    * Overwrite the content block 
        ```
        {% block content %}

        {% endblock %}
        ```
    * Add custom markup to the content block or include components
        ```
        {% block content %}
            {{ include('components/nameOfComponent.twig.html') }}
        {% endblock %}
        ```
        * If you need to conditionally show child templates (for example, a different homepage depending on the user type), use Twig conditionals
        ```
        {% block content %}
            {% if params.type == constant('ACCOUNT_TYPE_STUDENT') %}
                {{ include('pages/home/student-home.twig.html') }}
            {% elseif params.type == constant('ACCOUNT_TYPE_GRADER') %}
                {{ include('pages/home/grader-home.twig.html') }}
            {% elseif params.type == constant('ACCOUNT_TYPE_TEACHER') %}
                {{ include('pages/home/teacher-home.twig.html') }}
            {% elseif params.type == constant('ACCOUNT_TYPE_ADMIN') %}
                {{ include('pages/home/admin-home.twig.html') }}
            {% endif %}
        {% endblock %}
        ```
        
### New page example
* URL will be: site.tld/createAccount
* Create `pages/createAccount.php`
    ```
    <?php
    $parameters = array();
    renderPage("pages/create-account.twig.html", $parameters);
    ```
* Create `templates/pages/create-account.twig.html`
    ```
    {% extends "layout/base.twig.html" %}

    {% block title %}Create Account{% endblock %}

    {% block head %}
    {{ parent() }}
    {% endblock %}

    {% block content %}
        <h2>Create New Account</h2>
        {{ include('components/create-account.twig.html') }}
    {% endblock %}
    ```
*If you have trouble loading your new page, try clearing Twig's cache:* `rm -r cache/*`

### Composer
Composer offers several subcommands that may be necessary
* Use `composer list` to list all commands
* Use `composer install` to update dependencies, autoload lists, etc
