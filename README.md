# software-engineer-be

Coding challenge for Software Engineer Back-End http://archistar.ai/. Please see below for guide on how to run the project and tests.

## Overview
I have tried to deliver a project that simulates getting a geojson through ajax request from the server. The geojson is processed and added to the vuex store. The data is also being passed as a prop just to demonstrate ability of using multiple ways of passing data through components. Markers can be added or removed on the map by using the switches available on the project card or through the multi-select. Note that the multi-select and the project card switches are connected. You can also type into the multi-select to filter the selection and the list of card (this makes the UI a bit clunky but demonstrates the ability of using property sync and vue events).

## Running the project

First copy the `.env.example` file to `.env`
If you have php installed on your machine, you can run the following commands.

`php artisan serve`

You can alternatively use the Docker environment which comes with the project. You can run the command below from the root of the project to run the Docker environment.

`docker-compose up`

You can then connect to your machine using the command below

`docker exec -it SoftwareEngineerBE_web bash`

Steps below are **required** even if you ar not running docker

`composer install`

`php artisan migrate:fresh --seed`

#### Passport Setup

`php artisan passport:client --password`

**Copy the generated client id and secret onto .env**

Example:
```
OAUTH_CLIENT=1
OAUTH_SECRET=xmyyEd0UQqe6MOJIwyG2pLlaj1nGhXFBjRU8nbOo
```

#### PHP Unit Test

You can run the unit test using the command below

`./vendor/bin/phpunit`

You can also use the alias file supplied with the project for some useful shortcut.

```
source aliases
phpunit
```

#### CICD

All the necessary files for deploying the project into production is located under the folder `/docker`