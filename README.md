## Table of contents
- [Introduction](#introduction)
- [Requirements](#requirements)
- [Installation](#installation)
- [Usage](#usage)

## Introduction

Example Laravel backend application.

## Requirements
- [PHP](https://www.php.net/)
- [Composer](https://getcomposer.org/)
- [Docker](https://www.docker.com/)

## Installation

### 1. Clone this repo

```bash
git clone git@github.com:ventionteams-mikitasamak/artists-test.git
cd artists-test
```

### 2. Install dependencies

```bash
php composer.phar install
```

### 3. Configure environment

```bash
cp .env.example .env
```

### 4. Install sail and choose a database

```bash
php artisan sail:install
```

### 5. Start Laravel Sail

```bash
./vendor/bin/sail up -d
```

### 6. Generate application key

```bash
./vendor/bin/sail artisan key:generate
```

### 7. Seed the database

```bash
./vendor/bin/sail artisan migrate:refresh --seed
```

## Usage

### List available endpoints

```bash
./vendor/bin/sail artisan route:list --except-vendor
```

### Routes

#### GET /api/v1/artists

Get all artists

| parameter      | type   | description                | example                               |
|:---------------|:------:|:---------------------------|:--------------------------------------|
| filter[active] | bool   | Filter artists by activity | /api/v1/artists?filter[active]=1      |
| filter[email]  | string | Filter artists by email    | /api/v1/artists?filter[email]=foo@bar |
