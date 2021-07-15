# 0. Used Terms, library and dependency 
- ## Read me file
- ## PHPUnit
- ## Docker hub
- ## docker file 
- ## docker-compose file
- ## Ubantu OS on docker 
- ## images (php 7.4,mysql,adminer,docker-php-ext-install mysqli)
- ## DBTool for very basic database operation and migration available on github <https://github.com/Alok420/DBTool>
## Setup required
- ## database named as wamaship and host is "db"
- ## default username "root" and password "example"
- ## Need Docker-compose file
# 1. How to run the application with docker

# 2. How to run the application without docker 
## A. For windows
- ## Clone the project stored in github public repository <https://github.com/Alok420/PHP-Docker> <https://github.com/Alok420/PHP-Docker.git>
- ## Need server to setup 
- ## you can install Xampp which will configure the environment and run mysql and apache
- ## Open your browser and hit (localhost/phpmyadmin)
- ## create a database in phpmyadmin or direct in mysql cmd
- ## Now paste the cloned project into htdocks
- ## Now Configure the project
- ## Open connection.php locates in Config folder of your project and change username, password,host,database name here if required
- ## Migrate the table by hitting on the link <http://localhost/src/Config/Migrate.php>
- ## Create the relation by hitting on <http://localhost/src/Config/Migrate.php?type=relation>
- ## You are all done now
- ## Hit the URL <http://localhost/src>
- ## Now register from rgistration page 
- ## change value of role column of user table in mysql database using phpmyadmin or by hitting sql query to become normel user to admin
- ## by default it will be user you can change it as 'admin' for admin role
# 3. Some usefull docker commands or visit <https://docs.docker.com/engine/reference/run/>
## Docker Commands
### Create a Dockerfile in your PHP project or <https://hub.docker.com/_/php>
- This will run without any server like apache or ngnx because its running in a container php cli  

> FROM php:7.4-cli
COPY . /usr/src/myapp
WORKDIR /usr/src/myapp
CMD [ "php", "./your-script.php" ]

### Then, run the commands to build and run the Docker image:

> docker build -t my-php-app .

> docker run -it --rm --name my-running-app my-php-app

## To run docker-compose 
> docker-compose up -d
## To go in container
> docker exec -i -t container-id /bin/bash
## List
> docker images or docker image ls

> docker container ls
# 4.Unit test commands
> ./vendor/bin/phpunit
- ### fire commands in your terminal in project directory 
- ### php unit is usd in aur project
- ### Test directory locate to test folder in the project
- ### Only single function is tested  for demo only, thta is select which 
- ### mysql server with database wamaship is required and obviously username and pasword should be passed in DBToolTest.php
  Here in this test assertion is set to check that function is returning an array or not




