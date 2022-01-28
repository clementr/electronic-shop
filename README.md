# The electronic shop

## Introduction
This project is a php command application (php-cli) which aim to answer (the interview questions)[/instructions/README.md].

## Local developement

### Requirements

#### Using docker
* Docker https://docs.docker.com/get-docker/
* Docker compose https://docs.docker.com/compose/install/

#### Using your local php cli installation
* PHP (cli) > 8.0.14 

### Install
Within the project directory, run the following command to install the dependencies.
```
docker-compose run php composer.phar install
```

### Run
Within the project directory, run the following command to run the app.
```
docker-compose run php ./console shop:electronic question1
docker-compose run php ./console shop:electronic question2
```

### Run the tests
```
./vendor/bin/phpunit ./tests/
```