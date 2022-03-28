# Description

This repository contains a composer package library that can be included in a standard Laravel Installation by defining the package reference in the Laravel Application composer.json file. The package
provides functionality that defines the API Routes available for use to query Address Book data through an API Client such as Postman. Limited data validation is in place in an attempt to keep it as simple as
possible and to move through project requirements as quickly as possible.

## Assumptions

It is assumed that the system where this package is used, is already configured to host a Laravel Web Application. This package and it's documentaion does not detail the steps required to configure a web
server to serve the application through. It is further assumed that the system where this package is installed on, is correctly configured to make use of Composer and all required PHP modules. The package was
created to make use of PHP 7.3 on a Linux/Ubuntu Server environment running Apache 2 and MySQL. All other Apache configuration, such as mod_rewrite and mod_headers must be installed and enabled as per the
requirements to run a Laravel Based web application. Please see https://laravel.com/docs/9.x/installation for Laravel installation instructions.

Furthermore, to load the package source from repository, it is assumed that a valid github SSH key is generated on the system where the package is being installed. Failure to load the package from repository may be caused by an invalid GitHub SSH Key.

Finally, the commands and instructions in this file assumes that the package and application is configured on Ubuntu. Alternative commands must be used if another OS environment is used.


## REF:
composer create-project laravel/laravel example-app
cd example-app
php artisan serve

## Installation

To make use of this library, the base Laravel Application must first be installed, and configured to be accessed over a web browser. Once an application is available and ready to be served on a correctly 
configured web server setup, accessible through a web broser, additional steps must be taken to load the package library for use in the Laravel Web Application. For details about these steps, please see below.

### Environment

The most basic environment specific configuration should be enough for this package to be used. A MySQL Database Schema must be created during the Laravel Setup to support data for capture and query logic.

### Basic Laravel Installation via Composer

Create a new Laravel Web Application using composer:

```
composer create-project laravel/laravel example-app
```

Once the composer Laravel installation has completed, create a new MySQL database schema:
```
mysql -uroot -p
Enter password: 
Welcome to the MySQL monitor.  Commands end with ; or \g.
Your MySQL connection id is 6222
Server version: 8.0.28-0ubuntu0.21.10.3 (Ubuntu)
...
mysql> create schema sabl;
Query OK, 1 row affected (0.01 sec)

mysql> quit;
Bye
```

Next, edit the generated environment file (.env) to update the database credentials and schema information:

```
cd example-app
nano .env
```

For this example, only the Database credentials needs to be updated:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sabl
DB_USERNAME=**DB_USER**
DB_PASSWORD=**DB_PASS**
```

Save the file and close the editor.

Once the above has been done, and you are able to access the web application through a web browser, run the following command to include the package repository:

```
composer config repositories.pipiwyg vcs https://github.com/PipIWYG/relmaor.git
```

Save and close the file, and run the following command to include the package in the "requires" configuration key:
```
composer require pipiwyg/relmaor:1.2
```

### Package Discovery / Laravel Service Provider

It is possible to load the package service provider directly through composer, without the requirement of making changes to the root application configuration files. At the time of writing, package auto-discovery
failed to load the provider directly, if only defined in composer.json. An alternative to load the package service provider is to add the provider namespace to the application configuration file. To do this, do the follow:

```
nano config/app.php
```

Add the service provider to the providers array in this file:
```
'providers' => [
    ...
    PipIWYG\Roamler\RoamlerAppServiceProvider::class,
    ...
]
```

### Database Migrations

To make use of the package functionality, you must first run the database migrations, to create the required database tables. In the root of the application directory, run the following

```
php artisan migrate
```

Once the database migrations have executed without error, you may use an API client such as postman to create and query database records.

### Usage

Making use of an API Client or similar, define the API Endpoint URI to create the Contact Group records:

```
Method: POST
URI: http://**APPHOST**/api/v1/contact_group/create
```

#### Request Input Data

Specify the request input data to create a new contact group, by setting the body of the request to raw JSON format. Then define the request data as follows:
```
{
    "group_name": "Private"
}
```

Submit the request to retrieve response. If the request was successful, response will indicate success. Add an additional Contact Group for example purposes:
```
{
    "group_name": "Work"
}
```

At the time of writing, limited functionality is available to know what the group IDs are, however, the above two requests should have created 2 new Contact Group records in your database, with IDs 1, and 2.

To capture a new contact for a specific group, use the following:

```
Method: POST
URI: http://**APPHOST**/api/v1/contact/create
```

Private Contact Group Input:
```
{
    "first_name": "Private Test",
    "last_name": "Person",
    "group_id": 1
}
```

Work Contact Group Input:
```
{
    "first_name": "Work Test",
    "last_name": "Person",
    "group_id": 2
}
```

To capture contact email address, use the following:

```
Method: POST
URI: http://**APPHOST**/api/v1/email_address/create
```

Email Address Capture Input:
```
{
    "email_address": "me@me.com",
    "contact_id": 1
}
```

```
{
    "email_address": "me@you.com",
    "contact_id": 1
}
```

```
{
    "email_address": "me@work.com",
    "contact_id": 2
}
```

```
{
    "email_address": "you@work.com",
    "contact_id": 2
}
```

To capture contact phone numbers, use the following:

```
Method: POST
URI: http://**APPHOST**/api/v1/phone_number/create
```

Phone Number Capture Input:
```
{
    "phone_number": "+27214804321",
    "contact_id": 1
}
```

```
{
    "phone_number": "+27662834321",
    "contact_id": 1
}
```

```
{
    "phone_number": "+27214212212",
    "contact_id": 2
}
```

```
{
    "phone_number": "+27662832222",
    "contact_id": 2
}
```

To capture address data, use the following:

```
Method: POST
URI: http://**APPHOST**/api/v1/address/create
```

Address Capture Input:
```
{
    "street_address": "27 Montague Avenue",
    "city": "Johannesburg",
    "country": "South Africa",
    "contact_id": 1
}
```

```
{
    "street_address": "19 Baltimore Street",
    "city": "Cape Town",
    "country": "South Africa",
    "contact_id": 1
}
```

```
{
    "street_address": "2 Work Avenue",
    "city": "Johannesburg",
    "country": "South Africa",
    "contact_id": 2
}
```

```
{
    "street_address": "19 Work Street",
    "city": "Cape Town",
    "country": "South Africa",
    "contact_id": 2
}
```

Finally, to query the captured data for a contact, at the time of writing you will need to know the records IDs. To query data for the above requests:

```
Method: POST
URI: http://**APPHOST**/api/v1/contact/view
```

Request Input:
```
{
    "contact_id": 1
}
```

```
{
    "contact_id": 2
}
```

