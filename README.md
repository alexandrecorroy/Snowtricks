# Snowtricks

It's a Symfony 3.4 project. A community of snowboarder to learn how to make tricks.

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

### Prerequisites

What things you need to install the software and how to install them

```
PHP 7.1
MySQL 5.7
```

### Installing

First :

```
Git clone https://github.com/alexandrecorroy/Snowtricks.git
```

Update "parameters.yml.dist" with your parameters and rename it into "parameters.yml"

```
parameters:
    database_host: localhost
    database_port: null
    database_name: snowtricks
    database_user: root
    database_password: null
    mailer_transport: smtp
    mailer_encryption: ssl
    mailer_host:
    mailer_port:
    mailer_user:
    mailer_password:
    secret:
    kernel.secret:
    mailer_from: contact@snowtrick.com

```

Install Dependencies :

```
composer install
```

Install DB :

```
php bin/console doctrine:schema:update --force
```

Install fixtures :

```
php bin/console doctrine:fixtures:load
```

## Authors

* **Corroy Alexandre** - *Initial work* - [CORROYAlexandre](https://github.com/alexandrecorroy)

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

## SensioLabs Insight

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/a335a0a7-d213-4b27-bd40-e2c9feec75af/big.png)](https://insight.sensiolabs.com/projects/a335a0a7-d213-4b27-bd40-e2c9feec75af)
