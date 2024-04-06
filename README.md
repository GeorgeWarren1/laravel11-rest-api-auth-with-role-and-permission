<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Laravel11-Basic-Auth-Api-Starter

Project api using Laravel 11 with sanctum authentication and have role permission.


## Features

- Basic authentication with sanctum
- Role and permission
- Register and login
- Upload profile image
- Simple controller with helper


## Installation

Install my-project with npm

### 1. Clone Project

Find a location on your computer where you want to store the project. A directory made for projects is generally a good choice.

Launch a bash console there and clone the project.

```bash
  git clone https://github.com/organization/project.git
```
### 2 CD into project

You will need to be inside the project directory that was just created, so cd into it.

```bash
  cd project
```
### 3. Install composer dependencies

Whenever you clone a new Laravel project you must now install all of the project dependencies. This is what actually installs Laravel itself, among other necessary packages to get started.

```bash
  composer install
```
### 4. Copy the .env file

.env files are not generally committed to source control for security reasons. But there is a .env.example which is a template of the .env file that the project requires.

So you should make a copy of the .env.example file and name it .env so that you can setup your local deployment configuration in the next few steps.

```bash
  cp .env.example .env
```
### 5. Generate an app encryption key

Laravel requires you to have an app encryption key which is generally randomly generated and stored in your .env file. The app will use this encryption key to encode various elements of your application from cookies to password hashes and more.

Laravel’s command line tools thankfully make it easy to generate this. Run this command in the terminal to generate that key.

```bash
  php artisan key:generate
```
### 6. Make storage folder can access in public

All project image upload to storage folder

To make these files accessible from the web, you should create a symbolic link from public/storage to storage/app/public. Utilizing this folder convention will keep your publicly accessible files in one directory that can be easily shared across deployments when using zero down-time deployment systems like Envoyer.

To create the symbolic link, you may use the storage:link Artisan command:

```bash
  php artisan storage:link
```
### 7. Running Migrations

To run all of your outstanding migrations, execute the migrate Artisan command:

```bash
  php artisan migrate
```
### 8. Running Seeders

You may execute the db:seed Artisan command to seed your database. By default, the db:seed command runs the Database\Seeders\DatabaseSeeder class, which may in turn invoke other seed classes

```bash
  php artisan db:seed
```
