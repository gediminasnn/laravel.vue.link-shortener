
# URL Shortener Application Setup

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
    
3.  **Start the Docker Containers**
    
    Use Laravel Sail to start the Docker containers. Run the following command in your terminal:
    
    `./vendor/bin/sail up` 
    
    This command builds and starts all containers needed for the application. The first time you run this, it might take a few minutes to download and build everything.
    
4.  **Run Migrations**
    
    After the Docker containers are up and running, it's time to create the necessary database tables. In a new terminal window or tab, execute the following command:
    
    `docker exec -it php-l-jsn-vlink-shortener-laravel.test-1 php artisan migrate` 
    
    This command runs the migration files against the database to create the necessary tables for the application.
