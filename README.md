## Setting up

# README

## Introduction

This project uses [Laravel 9](https://laravel.com/docs/8.x), [InertiaJS](https://inertiajs.com/), [Vue](https://vuejs.org/) and [Tailwind](https://tailwindcss.com/). All the auths related stuff had been scaffolded using L[aravel Breeze](https://laravel.com/docs/8.x/starter-kits#laravel-breeze).

## First time setup

- Run `composer install`
- Run `npm install && npm run dev`
- Setup the environment variables in `.env` ( Most important database stuffs )
- Run `php artisan migrate` to run database migrations.
- Finally run `php artisan serve` or any equivalent command to start your laravel server.

## How to test

To run test you can these commands:

```
// To run backend feature and unit tests
./vendor/bin/pest
```

I wanted to implement tests for Vue components as well. But decided not to in favor of e2e test using Laravel Dusk. However, I didn't manage to start implementing them as well.

## Features

### Key features

#### Auth

- User can register and login
- User must verify email
- User can reset password

#### Folder

- User can create folder
- User can rename folder
- User can delete folder along with its subfolders and files under it.
- User can view folder tree in the sidebar

#### File

- User can upload files with no limitation
- User can download uploaded files
- User can delete files
- User can update and add labels to files.

#### Search

- User can search both folder and files

## Things I would like to improve

- Implement e2e test using Laravel Dusk
- Implement component tests for Vue components
- Refactor the codes.
