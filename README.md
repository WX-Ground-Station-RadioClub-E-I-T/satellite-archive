# Satellite Archive

![Home screen picture](./pics/1.gif)

Satellite Archive is a open source web tool for browsing the data of satellite observations. Easy to migrate to other study cases such as astronomical data.

Satellite Archive it's a was originally a web tool to navigate the data obtained in CESAR project. It's developed in `PHP` with `mySQL`, to filter and display the pictures obtained with the telescopes.

## Getting Started

### Prerequisites

Satellite Archive is built with **Docker**. In order to start the web application, you must have installed:

* [Docker](https://docs.docker.com/install/)
* [Docker-compose](https://docs.docker.com/compose/install/)

### Installing

To start Cosmos Archive just type in the root directory of the project:

```
$ docker-compose up -d
```

Then, it will deploy:
* **On port 80**: The Webserver application.
* **On port 8080**: PHPmyadmin connected to the database.
* **On port 3306**: MySQL database.

Then we have to import the `sample_database.sql` to MySQL. It can be done by accessing to PHPmyadmin on the port 8080, or by running the following command:

```
$ docker exec -i archive-db bash -c 'mysql -u root sample_database --password=tiger < /home/sample_database.sql'
```

### Building image

```
$ cd www
$ docker build -f ../bin/webserver/Dockerfile -t USERNAME/satellite-archive .
```

## Built With

* [Docker](https://www.docker.com/) - Contanier platform
* [Docker-compose](https://docs.docker.com/compose/) - Tool for defining and running multi-container Docker applications
* [Apache](https://httpd.apache.org/) - HTTP Server Project
* [PHP](https://php.net/)
* [MySQL](https://www.mysql.com/)
* [Bootstrap](https://getbootstrap.com/) - Frontend framework
* [npm](https://www.npmjs.com/) - Javascript package manager

## Authors

* **Fran Acién** - *Initial work* - [Github](https://github.com/acien101)

See also the list of contributors who participated in this project.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

## How it works

Satellite observations are uploaded [here](https://ftp.ea4rct.org/). When a observation is made, the pipeline insert into the database the information to be displayed on the Satellite Archive. See `sample_database` to see the structure followed.

The whole project is configured by *Docker* in the **Dockerfile** on `./bin`.

Npm modules are installed when the container starts, saved on `/var/www/node_modules` inside the Docker machine. With Apache, modules are redirected on `localhost/dep/`. See [index.php](./www/index.php) for more information.

### Debug mode

You can turn on `debug mode` to display the queries from the database. It can be turned on by changing the constant `DEBUG` on `./www/lib/conf.php` to true.
