# Blog

Developing a professional blog.

This guide will explain how to deploy my project using Composer.
Make sure you have Composer installed on your machine before starting with the command:

## First Step : 

Clone my project's repository from GitHub.
```bash
git clone https://github.com/pierrefayet/blog.git
cd projet5-blog# blog
```

## Second Step: Installing Dependencies

After cloning the repository, navigate to the project directory and use Composer to install the necessary dependencies.
```bash
cd project-directory
composer install
```
## Third Step: Installing the Mailer Service
For the mailer service, please install PHPMailer with Composer.
```bash
composer require phpmailer/phpmailer
```
 if you need more information, please consult the PHPMailer documentation at this address
 https://github.com/PHPMailer/PHPMailer.

## Using the Provided SQL Dump

To set up the database for this project, you can use the provided SQL dump located at /config/blog.sql
You can import this SQL file into your database, to create the necessary tables and initial data.
```bash
mysql -u your_username -p your_database_name < /src/Config/blog.sql
```

## Runnnig the local server

To run the project locally, you can use the following command to start the server:
```bash
php -S localhost:8080
```
You can access the locally deployed website by visiting the following URL:
http://localhost:8080/index.php?method=home&controller=HomePageController

Enjoy using my project!
