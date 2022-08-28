# Task-tracker

This is a Dockerized Task-tracker app, using PHP / Laravel

* PHP version:- 8.1.6

* Laravel version:- 9.19

## Dependencies

* a clone of this repo on your machine
* [Docker](https://docs.docker.com/)

## Run the app in your machine: -

* clone the project and setup the env file

```bash

git clone https://github.com/mohamedspicer/task-tracker
cd task-tracker
```

* rename the .env-example to .env and edit the environment variable as you like

* run the docker-compose

```bash
docker-compose up -d
```

## How to run the test suite

```bash

```

## Services

* the application
* mysql


## API Endpoints

### Default Path

```url
/api/v1/
```

#### GET /

Verifies that application is up and running.

##### Sample response

```json

{
    "status": 1, 
    "message": "Task-Tracker is runnig",
    "data": null 
}
```

### User related Endpoints

#### POST 
```http
/user/register
```

Creates a new user by register.

##### Sample body request (required)

```json

{
    "email": "test123@test.com",
    "password": "1234567",
    "password_confirmation": "1234567",
    "is_admin": 1,
    "name": "Test User"
}
```

##### Sample response

```json

{
    "status": 1,
    "message": "User Registers succefully",
    "data": {
        "user": {
            "email": "test123@test.com",
            "is_admin": 1,
            "name": "Test User",
            "updated_at": "2022-08-28T17:42:58.000000Z",
            "created_at": "2022-08-28T17:42:58.000000Z",
            "id": 2
        },
        "token": "3|4I88V1vcMdOJy9J7EybXMsinXfWLqX0ytkGEvRsf"
    }
}
```

#### POST
```http
/user/login
```

user login and retrieve token.

##### Sample body request (required)

```json

{
    "email": "test123@test.com",
    "password": "1234567",
}
```
##### Sample response

```json

{
    "status": 1,
    "message": "User Logged",
    "data": {
        "user": {
            "id": 1,
            "name": "Mohamed",
            "email": "test123@com.com",
            "email_verified_at": null,
            "created_at": "2022-08-28T16:49:21.000000Z",
            "updated_at": "2022-08-28T16:49:21.000000Z",
            "is_admin": 0
        },
        "api_token": "6|dAYhLX3HNNNFr5KUSUCFfNDXUkNbst04r9pb2iV2"
    }
}
```

#### GET
```http
/user/logout
```

user logout and revoke his tokens.


##### Sample response

```json

{
    "status": 1,
    "message": "Logout successfully",
    "data": null
}
```


### Project related Endpoints

#### GET
```http
/projects/all
```

Displays all projects that admin created (Allowed for admins only)

##### Sample response

```json

{
    "status": 1,
    "message": "All projects admin created",
    "data": [
        {
            "id": 1,
            "title": "titleProject",
            "description": "description Project"
        },
        {
            "id": 2,
            "title": "titleProject1",
            "description": "description Project1"
        },
        {
            "id": 3,
            "title": "titleProject2",
            "description": "description Project2"
        }
    ]
}
```

#### POST
```http
/projects/create
```

Create new project (Allowed for admins only)

##### Sample body request (required)

```json

{
    "title": "titleProject",
    "description": "description Project"
}
```
##### Sample response

```json

{
    "status": 1,
    "message": "project created succefully",
    "data": null
}
```

#### POST
```http
/projects/update/{id}
```

Update existing project (Allowed for admins only)

##### Sample body request (required)

```json

{
    "title": "editedTitleProject",
}
```
##### Sample response

```json

{
    "status": 1,
    "message": "Project updated successfully",
    "data": null
}
```

#### POST
```http
/projects/delete/{id}
```

Delete existing project (Allowed for admins only).
if project contains tasks the project will not deleted


##### Sample response

```json

{
    "status": 1,
    "message": "Project deleted successfully",
    "data": null
}
```

### Task related Endpoints

#### POST
```http
/tasks/all
```

Displays all tasks that admin created for specific project (Allowed for admins only)

##### Sample body request (required)

```json

{
    "project_id": 2
}
```
##### Sample response

```json

{
    "status": 1,
    "message": "All tasks",
    "data": [
        {
            "id": 1,
            "title": "title task",
            "description": "description task",
            "project_id": 2,
            "assigned_to": 1,
            "created_at": "2022-08-28T18:28:20.000000Z",
            "updated_at": "2022-08-28T18:28:20.000000Z",
            "submitted": 0
        }
    ]
}
```

#### GET
```http
/tasks/userTasks
```

User Tasks which assigned to him (Allowed to users)

##### Sample response

```json

{
    "status": 1,
    "message": "All tasks",
    "data": [
        {
            "id": 2,
            "title": "title task",
            "description": "description task",
            "project_id": 2,
            "assigned_to": 1,
            "created_at": "2022-08-28T18:28:28.000000Z",
            "updated_at": "2022-08-28T18:28:28.000000Z",
            "submitted": 0
        }
    ]
}
```

#### POST
```http
/tasks/update/{id}
```

Update existing task (Allowed for admins only)

##### Sample body request (required)

```json

{
    "title": "editedTitleTask",
}
```
##### Sample response

```json

{
    "status": 1,
    "message": "Task updated successfully",
    "data": null
}
```

#### POST
```http
/tasks/delete/{id}
```

Delete existing task (Allowed for admins only).

##### Sample response

```json

{
    "status": 1,
    "message": "Project deleted successfully",
    "data": null
}
```

#### POST
```http
/tasks/submit/{id}
```

Submit task (Allowed for user).

##### Sample response

```json

{
    "status": 1,
    "message": "Task submitted succesfully",
    "data": null
}
```
