# Simple Address Book Library - Core

This repository contains a composer package library that can be included in a Laravel Installation. The package
provides functionality that defines the database migrations and API routes available for use to manage and maintain
Address Book data through an API Client such as Postman. Limited data validation is in place in an attempt to keep
it as simple as possible for demonstration purposes.

## Assumptions
It is assumed that the system where this package is installed on is correctly configured to host a Laravel Web
Application. This documentaion details the most basic installation requirements to install Laravel and the 
commands to run to load the package library from source control in a standard Laravel Application.

Furthermore, to load the package source from repository, it is assumed that a valid GitHub SSH key is generated on
the system where the package is being installed. Alternatively, without an Github SSH Key, the repository reference
URL to use to install the package may need to change from SSH to HTTPS.

The package was created to make use of PHP 7.3 on a Linux/Ubuntu Server environment making use of a local MySQL
Server Database.

For detailed instructions on how to install Laravel, please see https://laravel.com/docs/9.x/installation.

## Installation

To make use of this library, the base Laravel Application must first be installed, and configured to be accessed over a web browser. Once an application is available and ready to be served on a correctly 
configured web server setup, accessible through a web broser, additional steps must be taken to load the package library for use in the Laravel Web Application. For details about these steps, please see below.

### Environment

The most basic environment specific configuration should be enough for this package to be used. A MySQL Database Schema must be created during the Laravel Setup to support data for capture and query logic.

### Basic Laravel Installation via Composer

Create a new Laravel Web Application using composer:

```
$ composer create-project laravel/laravel example-app
$ cd example-app
```

Once the composer Laravel installation has completed, create a new MySQL database schema to host application data.
```
$ mysql -uroot -p
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

Next, edit the generated environment file (.env) to update the database credentials and schema information. Should 
no .env file have been automatically created during the laravel installation, copy the .env.example file in the root
directory to .env and then edit the environment file.

```
$ nano .env
```

For this example, only the Database credentials needs to be updated in the environment configuration:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sabl
DB_USERNAME=**YOUR_DB_USER**
DB_PASSWORD=**YOUR_DB_PASS**
```

Save the file and close the editor.

Next, run the following commands to include and install the package repository from source control.

NOTE: If no repository SSH key exists to load the library source during installation, make use of the HTTPS section below
instead of the SSH section

**SSH**
```
$ composer config repositories.pipiwyg vcs git@github.com:PipIWYG/sabl-core.git
$ composer require pipiwyg/sabl-core:1.1
```

**HTTPS**
```
$ composer config repositories.pipiwyg vcs https://github.com/PipIWYG/sabl-core.git
$ composer require pipiwyg/sabl-core:1.1
```

### Database Migrations

To make use of the package functionality, run the database migrations to create the required database tables. In the 
root of the application directory, run the following

```
php artisan migrate
```

Once the database migrations have executed without error, you may use an API client such as postman to create and query 
database records. Serve the application to be able to access the App through a web browser, or to use an API client. 
Once the application is accessible through a web browser, see the usage instructions below for details on how to make
use of the package functionality

```
php artisan serve
```

### Usage

Making use of an API Client or similar, define the API Endpoint URI to create or query record data.

--------------------
#### Request Methods (VERBS)
**POST**

The POST request method is used to Query Record Data for all available endpoint URIs, and requires request input data specifying the record ID to query

Example: `Endpoint URI: /api/v1/address_book`
```
{
    "id": 1
}
```
The above request will return data for an address book record with ID:1

**GET**

The GET request method is used to Query Record Data for all available endpoint URIs, and requires a record ID defined in the Request Endpoint URI.

Example: `Endpoint URI: /api/v1/address_book/1`

The above request will return data for an address book record with ID:1

**PUT**

The PUT request method is used to Create Record Data for all available endpoint URIs, and requires record input data with the correct paraneters.

Example: `Endpoint URI: /api/v1/address_book`

```
{
    "name": "Private Address Book"
}
```

The above request will create a Address Book record with a name of "Private Address Book"

--------------------
### Available Endpoints and Request Examples

Specify the request input data to create a new contact group, by setting the body of the request to raw JSON format.

```
Endpoint: /api/v1/address_book
Request Method: PUT
```

Request Data
```
{
    "name": "Private Address Book"
}
```
The above request will create a new Address Book record

```
Endpoint: /api/v1/address_book
Request Method: POST
```

Request Data
```
{
    "id": 1
}
```
The above request will return record data for Address Book record ID 1

```
Endpoint: /api/v1/address_book/1
Request Method: GET
```

The above request will return record data for Address Book record ID 1

```
Endpoint: /api/v1/contact
Request Method: PUT
```

Request Data
```
{
    "first_name": "Peter",
    "last_name": "von Lichtenstein",
    "address_book_id": 1
}
```
The above request will create a new Contact record in Address Book with ID 1

```
Endpoint: /api/v1/contact
Request Method: POST
```

Request Data
```
{
    "id": 1
}
```
The above request will return record data for Contact record ID 1

```
Endpoint: /api/v1/contact/1
Request Method: GET
```

The above request will return record data for Contact record ID 1

```
Endpoint: /api/v1/address
Request Method: PUT
```

Request Data
```
{
    "street_address_primary": "109 Westside Studios",
    "street_address_secondary": "Buitengracht Street",
    "city": "Cape Town",
    "country": "South Africa",
    "contact_id": 1
}
```
The above request will create a new Address record for a contact record with ID 1

```
Endpoint: /api/v1/address
Request Method: POST
```

Request Data
```
{
    "id": 1
}
```
The above request will return record data for Address record ID 1

```
Endpoint: /api/v1/address/1
Request Method: GET
```

The above request will return record data for Address record ID 1

```
Endpoint: /api/v1/email_address
Request Method: PUT
```

Request Data
```
{
    "email_address": "peter@von-lichtenstein.com",
    "contact_id": 1
}
```

```
{
    "email_address": "petervonlichtenstein@gmail.com",
    "contact_id": 1
}
```
The above requests will create a new Email Address record for a contact record with ID 1

```
Endpoint: /api/v1/email_address
Request Method: POST
```

Request Data
```
{
    "id": 1
}
```
The above request will return record data for Email Address record ID 1

```
Endpoint: /api/v1/email_address/1
Request Method: GET
```

The above request will return record data for Email Address record ID 1

```
Endpoint: /api/v1/phone_number
Request Method: PUT
```

Request Data
```
{
    "phone_number": "+155523232854",
    "contact_id": 1
}
```
The above requests will create a new Phone Number record for a contact record with ID 1

```
Endpoint: /api/v1/phone_number
Request Method: POST
```

Request Data
```
{
    "id": 1
}
```
The above request will return record data for Phone Number record ID 1

```
Endpoint: /api/v1/phone_number/1
Request Method: GET
```
The above request will return record data for Phone Number record ID 1

```
Endpoint: /api/v1/group
Request Method: PUT
```

Request Data
```
{
    "name": "Private Contact Group"
}
```
The above requests will create a new Group record

```
Endpoint: /api/v1/group
Request Method: POST
```

Request Data
```
{
    "id": 1
}
```
The above request will return record data for Group record ID 1

```
Endpoint: /api/v1/group/1
Request Method: GET
```
The above request will return record data for Group record ID 1

```
Endpoint: /api/v1/contact_group
Request Method: PUT
```

Request Data
```
{
    "contact_id": 1,
    "group_id": 1,
}
```
The above request will attach a group record with an ID of 1 to a Contact record with an ID of 1

```
Endpoint: /api/v1/contact_group
Request Method: POST
```

Request Data
```
{
    "id": 1
}
```
The above request will return record data for Contact Group record ID 1

```
Endpoint: /api/v1/contact_group/1
Request Method: GET
```
The above request will return record data for Contact Group record ID 1


```
Endpoint: /api/v1/find
Request Method: POST
```

Request Data
```
{
    "query": "lichten",
    "type": "email"
}
```

The above request will query contacts with an email address containing 'lichten'

Request Data
```
{
    "query": "lichten",
    "type": "contact"
}
```
The above request will query contacts with a first name or last name containing 'lichten'

### Unit Tests

The package unit tests can be executed, provided that Unit Tests are correctly configured in the Root Application.
This configuration directives are not currently included within the package source, but test cases have been created in
support of the correct unit testing configuration.

Laravel makes use of SQLite to run Unit Tests on data in Memory. To get this to work correctly, follow these steps.

First, make sure that the PHP SQLite Module is installed on the target system
```
apt install php7.3-sqlite3
```
Next, update the database configuration file, and define the SQLite connection to reflect in-memory unit tests
```
nano config/database.php
``` 

```
...
'connections' => [
    'sqlite' => [
        'driver' => 'sqlite',
        'database' => ':memory:',
        'prefix' => '',
    ]
],
...
```
Then enable SQLite in phpunit.xml by adding the environment variable `DB_CONNECTION`:
```
    ...
    <php>
        <server name="APP_ENV" value="testing"/>
        <server name="DB_CONNECTION" value="sqlite"/>
        ...
    </php>
    ...
```

With that out of the way, all that's left is configuring the base TestCase class to use migrations and seed the database before each test. To do so, add the `DatabaseMigrations` trait, and then add an Artisan call on the `setUp()` method:

```
<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed --class=AddressBookSeeder');
    }
}
```

Optionally, you can also add the test command to `composer.json` to make it easier to call phpunit to run test cases:

```
    ...
    "scripts": {
        "test": [
            "vendor/bin/phpunit"
        ], 
        ...
    },
    ...
```

The test command will be available like this:

```
$ composer test
```
