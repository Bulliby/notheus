# Initialisation

## Zshrc alias

```shell
pro='docker exec --user www-owner -it projects-server-1'
```

## Dev

### Env

```
#!/usr/bin/env bash
MYSQL_ROOT_USER="root"
MYSQL_ROOT_PASSWORD="root"
MYSQL_USER="dev"
MYSQL_PASSWORD="dev"
MYSQL_DATABASE="projects"
SITE_PATH="/home/perso/projects"
MYSQL_DATA_PATH="/home/perso/projects/docker/mysql-data"
LISTENING_IP="192.168.1.173"
```

### DB

```shell
pro php bin/console doctrine:schema:create
```

## Test

### Mysql

```shell
docker exec -it projects-mysql-1 mysql -uroot -proot
```

```mysql
CREATE USER 'test'@'%' IDENTIFIED BY 'test';
GRANT ALL ON *.* TO 'test'@'%';
FLUSH PRIVILEGES;
```

### Database

```shell
pro php bin/console --env="test" doctrine:database:create
pro php bin/console --env="test" doctrine:schema:create
pro php bin/console --env="test" doctrine:fixture:load
pro php bin/phpunit
```