# URL Shortener Application
![main](https://github.com/gediminasnn/php-l-jsn-v.link-shortener/assets/70708109/ff2a0241-54e1-4175-a3fe-7fb6f022af79)

This URL shortener application is built with Laravel and Vue.js. It provides a simple web interface to shorten URLs and implements checks against the Google Safe Browsing API (or similar) to ensure URL safety. This document outlines the steps required to set up the application on your local development environment.

## Prerequisites

Before proceeding with the setup, ensure you have the following installed on your machine:

-   Docker
-   Docker Compose
-   Composer

## Installation Steps

0.  **Clone the Repository**
    
    First, clone the repository to your local machine. Open a terminal and run the following command:
    
    `git clone git@github.com:gediminasnn/link-shortener.git` 
    
    (Optional) Replace `git@github.com:gediminasnn/link-shortener.git` with the URL of repository.
    
1.  **Navigate to the Application Directory**
    
    Change directory to the application root:
    
    `cd link-shortener` 
    
    (Optional) Replace `link-shortener` with the path where you cloned the repository.
    
2.  **Prepare the Environment File**
    
    Prepare the application's environment file. Locate the `env.example` file in the application root and rename it to `.env`. Optionally, edit the `.env` file to adjust any environment variables specific to your setup.

3.  **Install Composer Dependencies**

    Before starting the Docker containers, install the project's PHP dependencies using Composer. Open a terminal and navigate to your project directory. Then, run the following command:

    `composer install`

    This command downloads and installs all the PHP libraries your project requires based on the `composer.json` file.

4.  **Start the Docker Containers**
    
    Use Laravel Sail to start the Docker containers. Run the following command in your terminal:
    
    `./vendor/bin/sail up` 
    
    This command builds and starts all containers needed for the application. The first time you run this, it might take a few minutes to download and build everything.
    
5.  **Setup Configuration Cache**

    Before starting the Docker containers, cache the application's configuration for performance optimization. Open a new terminal from project root directory and run the following command:

    `./vendor/bin/sail php artisan config:cache`

    This command generates a cached file of all configuration values, which Laravel can load significantly faster than reading configuration files from disk every time. This improves the overall performance of the application.
    
6.  **Run Migrations**
    
    After the Docker containers are up and running, it's time to create the necessary database tables. In the terminal, execute the following command:
    
    `docker exec -it link-shortener-laravel.test-1 php artisan migrate` 
    
    This command runs the migration files against the database to create the necessary tables for the application.

7.  **Install Node.js Dependencies**
    
    With the Docker containers up and running, you'll next need to install the Node.js dependencies required for the front-end part of the application. In the terminal, execute the following command:
    
    `./vendor/bin/sail npm install` 
    
    This command will use Docker to run npm install within the application's container, ensuring that all Node.js dependencies are installed according to the package.json file located in the application root.

8.  **Compile Front-End Assets**
    
    After the installation of Node.js dependencies, you must compile the front-end assets using Laravel Mix. In the same terminal window or tab, execute the following command:
    
    `./vendor/bin/sail npm run dev` 
    
    This command triggers Laravel Mix to compile and publish the assets, such as CSS and JavaScript files, making them available for use by the application.

9.  **(Optional) Run Tests**
    
    Ensure that your Docker containers are still up and running. Open a new terminal window or tab and execute the following command:
    
    `./vendor/bin/sail php artisan test` 
    
    This command will use Laravel's built-in test runner to execute your application's test suite. It will run all the tests located in the tests directory of your application.

    By completing this step, you will have fully set up your URL shortener application on your local development environment, ensuring it is ready for further development, testing, or deployment.

## API Documentation

You can send HTTP requests to the following RESTful endpoints:

1. Get CSRF token :

    `GET /token`
    ```
    HTTP/1.1 200 OK
    Content-Type: text/html; charset=UTF-8

    qyFMrXaSyGee03wjfE2GT1WSyFTBfFMfCrmO64Xk
    ```

2. Shorten url
    ```
    GET /shorten-url
    X-CSRF-TOKEN: {{CSRFTOKEN}}
    Content-Type: application/json

    {
        "long_url": "https://gemini.google.com/"
    }
    ``` 

    ```
    HTTP/1.1 200 OK
    Content-Type: application/json
    
    {
        "url": "http://localhost/xSU7c2"
    }
    ```

2. Shorten foldered url
    ```
    GET /shorten-foldered-url
    X-CSRF-TOKEN: {{CSRFTOKEN}}
    Content-Type: application/json

    {
        "long_url": "https://www.facebook.com/",
        "folder": "meta"
    }

    ``` 

    ```
    HTTP/1.1 200 OK
    Content-Type: application/json
    
    {
        "url": "http://localhost/meta/M3hOWB"
    }
    ```

## Pages Documentation

### Successful link shortening display
![success](https://github.com/gediminasnn/gediminasnn/assets/70708109/16d90d0d-26f3-4c4e-bc1b-dd87cb200ea9)

### Unsuccessful link shortening display
![error](https://github.com/gediminasnn/gediminasnn/assets/70708109/4d155757-eefb-4f87-9f10-7331cabc4a17)
