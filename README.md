# Video upload API in Symfony 6

Permit to upload videos and link it to categories.

## Set up the project

### Requirements

-PostgreSQL server running

-Symfony-CLI

-PHP8.2 and Composer in PATH

```
git clone https://github.com/morgane-luminous/video-upload-API.git ./video-upload-API
cd ./video-upload-API
composer install
php bin/console lexik:jwt:generate-keypair
symfony server:start
```
Rename ./.env.example into ./.env, then change DATABASE_URL value.

```
php .\bin\console doctrine:database:create
php .\bin\console doctrine:migrations:migrate
```

You may have to change User provider (config/packages/security.yaml:5) to provide your users from an other application.

## Application guide

### Authentication

The API is completely secure, you will have to log in to request it.

Authentication is handled by Json Web Token, through
LexikJWTAuthenticationBundle.

To log in, hit `localhost:8000/api/login_check`, with body request :

```
{
    "username": "yourusername",
    "password":"yourpassword"
}
```

You will receive a json response, with the token :

```
{
    "token": "eyJ0eXAiOi-dsferg0g..."
}
```

For your next requests, set a Header `Authorization: Bearer *yourtoken*`

### API

With this API, you will be able to upload either video file nor external URI. 

You can find a complete json documentation about the API on `http://localhost:8000/docs.json` or using the command `php bin/console api:openapi:export`, but here is a quickview of availables routes.

### Videos

*src/Entity/Video.php*

Note that it only accept mime types declared in Video::AUTHORIZED_MIME_TYPES.

- GET `/videos`: Get list of videos
- GET `/videos/{id}`: Get a specific video
- POST `/videos`: Only admins can post videos. You will have to send form-data:

```
file: Yourfile.mp4
title: "Your title"
description: "..." (optional
categories[]: "/categories/1" (optional)
```

- PATCH `/videos/{id}`: Only video creator can patch it. Body example:

```
{
    "title": "Changed title",
    "categories": [
        "/categories/1",
        "/categories/2",
    ]
}
```

You have to pass through this method and endpoint to add categories to a video. Send an exhaustive array of categories, it will replace the current ones.

- DELETE `/videos/{id}`: Only video creator can delete it.

### Categories

*src/Entity/Category.php*

- GET `/categories`: Get list of categories

- GET `/categories/{id}` : Get a specific category

- POST `/categories`: Only admins can post categories. Name has to be unique. Body example:

```
{
    "name": "Category #1"
}
```

- PATCH `/categories/{id}`: Only category creator can patch it. Body example:

```
{
    "name": "Category changed"
}
```

- DELETE `/categories/{id}`: Only category creator can delete it.

### To do now

To limit file size, uncomment "maxSize" of "Assert\File" set on Video::$file

Limit access to GET videos and their categories to some users (clients, admins, training program member...)

Set up provider to get users from prod db