# The Electronic shop

## Introduction
This project is a php command-line application (php-cli) which aims to comply with [the given instructions](/instructions/README.md).

## Local developement

### Requirements

#### Using docker
* Docker https://docs.docker.com/get-docker/
* Docker compose https://docs.docker.com/compose/install/

#### Using your local php cli installation
* PHP (cli) > 8.0.14 

### Install
Within the project directory, run the following command to build the docker container & install the dependencies for the app.
```
docker-compose run php ./composer.phar install
```

### Run
Within the project root directory, execute the following commands to run the app.
```
docker-compose run php ./console shop:electronic question1
docker-compose run php ./console shop:electronic question2
```

### Run the tests
```
docker-compose run php ./vendor/bin/phpunit ./tests/
```