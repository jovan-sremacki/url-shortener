# Laravel Vue URL Shortener

## About

This project is a URL shortening service built with Laravel and Vue.js, utilizing Docker for easy development and deployment. The application allows users to enter a long URL and receive a shortened link that redirects to the original URL. It features a Vue.js frontend for user interaction and a Laravel backend for processing and storing URLs.

## Features

- URL shortening with a simple, clean user interface
- Full REST API for creating and retrieving shortened URLs
- Integration with Google Safe Browsing API to ensure URL safety

## Technologies

- **Frontend**: Vue.js
- **Backend**: Laravel
- **Database**: MySQL
- **Containerization**: Docker

## Prerequisites
Before beginning the setup, ensure the following software is installed on your machine:

- Docker: For creating and managing containers. Docker Desktop is recommended for Windows and macOS users.
- Docker Compose: Typically included with Docker Desktop on Windows and macOS, but may need to be installed separately on Linux.
- Git: For cloning the repository (if applicable).
- PHP: If you plan to run any PHP commands outside of Docker containers.
- Composer: For managing PHP dependencies.
- Node.js and npm: For managing JavaScript dependencies and running scripts (if your project uses Node.js).


## Setting up the environment

```
git clone git@github.com:jovan-sremacki/url-shortener.git

cd url-shortener

sudo chmod +x ./setup.sh

./setup.sh
```

If you're encountering issues with the automated setup script or prefer to configure your environment manually, the following guide offers a detailed, step-by-step approach to setting up your project environment.

## Manual Environment Setup Instructions

1. Clone the Repository:

```
git clone git@github.com:jovan-sremacki/url-shortener.git
```

- Navigate into the project directory:<br>
   `cd url-shortener`<br>

2. Prepare .env and .env.testing Files:

- Copy the .env.example file to .env and .env.testing if they don't already exist:

```
cp .env.example .env
cp .env.example .env.testing
```

- Edit the .env and .env.testing files to update the necessary values. Here are the details for the database configuration and other relevant settings that need to be replaced:

<b>.env</b>

```
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=your-db-name
DB_USERNAME=root
DB_PASSWORD=your-password
```

<b>.env.testing</b>

```
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=your-db-name
DB_USERNAME=root
DB_PASSWORD=your-password
```

- Make sure to replace any placeholder values with actual data relevant to your project and environment setup.

3. Start Docker Containers:

- Use Docker Compose to build and start the containers specified in your docker-compose.yml file. Typically, this can be done with the following command from the root of your project directory:

`docker-compose up -d`<br>

- This command will download, build, and start all containers in the background.

4. Install PHP Dependencies:

- Run Composer to install PHP dependencies. This can be done inside the app container (assuming app is the name of your application container):

`docker-compose exec app composer install`

5. Generate Laravel Application Key:

- Generate the application key for Laravel:

`docker-compose exec app php artisan key:generate`<br>

6. Run Migrations (Optional):

`docker-compose exec app php artisan migrate`

7. Install Node.js Dependencies (if applicable):

```
npm install
npm run dev
```

Access the Application:

At this point, the application should be running and accessible via the configured port on `localhost:8000`.