# Laravel API for User Registration and Email Sending

## Description

This is a RESTful API built with Laravel that allows users to register themselves by providing their email addresses. Upon registration, the API saves the user's email to the database and sends a welcome email using the Gmail API.

## Prerequisites

Before running this API, ensure you have the following installed on your local machine:

-   Docker
-   Docker Compose

## Getting Started

1. Clone this repository to your local machine.
2. In the root directory of the project, create a new file named `.env` and copy the contents from `.env.example` into it.
3. Configure the necessary environment variables in the `.env` file. Make sure to set up the Gmail API credentials and the database connection details.

## Installation and Setup

To build and run the Docker containers for the Laravel API, execute the following command in the terminal:

```bash
docker-compose up --build

```

Once the containers are up and running, you can access the API at http://localhost:8000/.

API Endpoints
Register a User
URL: /api/register
Method: POST
Request Body:
{
"email": "user@example.com"
}

Response:
Status: 201 Created
Body:
{
"message": "User registration successful.",
"data": {
"email": "user@example.com"
}
}

Notes: This endpoint registers a user by saving their email address to the database and sends a welcome email to the user.
Environment Variables
The following environment variables are used in this project:

APP_NAME: The name of the Laravel application.
APP_ENV: The environment in which the application is running (e.g., local or production).
APP_KEY: The application key used for encryption.
APP_DEBUG: Set to true for debugging mode.
APP_URL: The base URL of the application.
DB_CONNECTION: The database connection type (pgsql for PostgreSQL).
DB_HOST: The database host.
DB_PORT: The database port.
DB_DATABASE: The name of the PostgreSQL database.
DB_USERNAME: The database username.
DB_PASSWORD: The database password.
MAIL_MAILER: The mail driver to use (smtp for Gmail API).
MAIL_HOST: The SMTP host for Gmail API (smtp.gmail.com).
MAIL_PORT: The SMTP port for Gmail API (587).
MAIL_USERNAME: The Gmail API email address for sending emails.
MAIL_PASSWORD: The Gmail API application-specific password.
MAIL_ENCRYPTION: The encryption type for Gmail API (tls).
MAIL_FROM_ADDRESS: The email address to be used as the "from" address in sent emails.
MAIL_FROM_NAME: The name to be used as the "from" name in sent emails.

Contributing
If you'd like to contribute to this project, please follow these guidelines:

1. Fork the repository.
2. Create a new branch for your changes.
3. Make your contributions.
4. Commit your changes and push them to your forked repository.
5. Submit a pull request.

License
This project is licensed under the MIT License. See the LICENSE file for details.

Please make sure to replace the placeholders with appropriate values, such as API endpoint descriptions, environment variables, and other relevant information specific to your project.

Feel free to customize and expand the README.md as needed to provide comprehensive documentation for your Laravel API project.
