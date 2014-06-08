pfe
===

Our Graduation Projet


Initialisation
--------------
Clone and Follow the steps: https://github.com/BodinSamuel/vm_dev

You are done (barely)

Requirement
-----------
if you are not using the box, or for the prod environnement

* Debian wheezy 64bit

* Nginx

* php5-fpm
  * php5-memcached php5-mysql php5-pdo
  * imagick mcrypt

* composer

* MySQL

* memcached
  * sudo apt-get install memcached

* elasticsearch

* RabbitMQ
  * not used yet but soon to be


Nginx and laravel
-----------------
Nginx may not understand rewrited url, you need to modify the config file

 * Install emacs (or any editor), if not installed.

 * edit vhost_autogen.conf and replace it by __doc__/host.dev.nginx

 * sudo /etc/init.d/nginx restart



Know Error
-----

- if vagrant can't up or up be no connection: reboot pc

- if everypage is 502:
    - sudo tail -f /var/log/nginx/pfe.dev.error.log
        - if can't connect to php-fpm
            - cd /var/run && sudo chmod 666 php5-fpm.sock

- restart php-fpm cause to rechmod php5-fpm.sock
  - cd /var/run && sudo chmod 666 php5-fpm.sock
