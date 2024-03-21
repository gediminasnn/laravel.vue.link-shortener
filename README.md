# URL Shortener Application Setup

![main](https://github.com/gediminasnn/php-l-jsn-v.link-shortener/assets/70708109/ca770a24-2cc4-4208-862c-88ae53e98a99)

This URL shortener application is built with Laravel and Vue.js. It provides a simple web interface to shorten URLs and implements checks against the Google Safe Browsing API (or similar) to ensure URL safety. This document outlines the steps required to set up the application on your local development environment.

## Prerequisites

Before proceeding with the setup, ensure you have the following installed on your machine:

-   Docker
-   Docker Compose

## Installation Steps

1.  **Clone the Repository**
    
    First, clone the repository to your local machine. Open a terminal and run the following command:
    
    `git clone <repository-url>` 
    
    Replace `<repository-url>` with the actual URL of your repository.
    
2.  **Navigate to the Application Directory**
    
    Change directory to the application root:
    
    `cd path/to/php-l-jsn-vlink-shortener-laravel` 
    
    Replace `path/to/php-l-jsn-vlink-shortener-laravel` with the actual path where you cloned the repository.
    
3.  **Prepare the Environment File**
    
    Before starting the Docker containers, prepare the application's environment file. Locate the `env.example` file in the application root and rename it to `.env`. Optionally, edit the `.env` file to adjust any environment variables specific to your setup.

4.  **Start the Docker Containers**
    
    Use Laravel Sail to start the Docker containers. Run the following command in your terminal:
    
    `./vendor/bin/sail up` 
    
    This command builds and starts all containers needed for the application. The first time you run this, it might take a few minutes to download and build everything.
    
5.  **Run Migrations**
    
    After the Docker containers are up and running, it's time to create the necessary database tables. In a new terminal window or tab, execute the following command:
    
    `docker exec -it php-l-jsn-vlink-shortener-laravel.test-1 php artisan migrate` 
    
    This command runs the migration files against the database to create the necessary tables for the application.

6.  **Install Node.js Dependencies**
    
    With the Docker containers up and running, you'll next need to install the Node.js dependencies required for the front-end part of the application. Open a new terminal window or tab and execute the following command:
    
    `./vendor/bin/sail npm install` 
    
    This command will use Docker to run npm install within the application's container, ensuring that all Node.js dependencies are installed according to the package.json file located in the application root.

7.  **Compile Front-End Assets**
    
    After the installation of Node.js dependencies, you must compile the front-end assets using Laravel Mix. In the same terminal window or tab, execute the following command:
    
    `./vendor/bin/sail npm run dev` 
    
    This command triggers Laravel Mix to compile and publish the assets, such as CSS and JavaScript files, making them available for use by the application.
