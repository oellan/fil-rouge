# Buzzy

## Dependencies

* Apache2 (with mod_rewrite installed)
* MariaDB
* PHP 7.4+
* Composer

## Installation (step by step)

### Install dependencies with Composer

```shell
composer install
```

### Create Twig cache folder

```shell
mkdir -p .twig_cache
chmod 0777 .twig_cache
```

### Enable mod_rewrite on Apache2 HTTPd

```shell
a2enmod rewrite
```

### Create and enable Buzzy virtual host

```shell
cat <<<EOF > /etc/apache2/sites-available/buzzy.conf
Listen 8080
<VirtualHost *:8080>
        ServerAdmin webmaster@localhost
        DocumentRoot /var/www/buzzy/BadBox/public
        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
EOF
a2ensite buzzy.conf
```

Put this folder in `/var/www`. Change the configuration, and the destination folder if needed.

### Create and connect to database

```shell
nano .env
```

Edit `.env` and put your own information. If your database isn't named `buzzy`, change it in the following SQL script.

```sql
DROP DATABASE IF EXISTS buzzy;

CREATE DATABASE `buzzy`
    COLLATE utf8mb4_bin;

DROP TABLE IF EXISTS `buzzy`.users;

CREATE TABLE `buzzy`.`users`
(
    `id`       INT AUTO_INCREMENT PRIMARY KEY,
    `username` TEXT NOT NULL,
    `email`    TEXT NOT NULL,
    `password` TEXT NOT NULL,
    CONSTRAINT `users_email_uindex`
        UNIQUE (`email`)
        USING HASH,
    CONSTRAINT `users_username`
        UNIQUE (`username`)
        USING HASH
);
```
