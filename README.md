# Task Management
This is a task management application where user can create, update, delete and mark his tasks as pending,completed, in-progress.

## Requirements
- php8.2 or higher
- necessary php extensions
- composer
- node js
- pnpm
## Installation
```sh
composer install
```
## Dev server
```sh
composer dev
```

## Api endpoints
- /api/user `GET`
- /api/auth/login `POST`
- /api/auth/register `POST`
- /api/auth/logout `POST`
- /api/tasks `GET`
- /api/tasks/{id} `GET`
- /api/tasks `POST`
- /api/tasks/{id} `PUT`
- /api/tasks/{id} `DELETE`