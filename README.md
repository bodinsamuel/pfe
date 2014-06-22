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
  * sudo apt-get install elasticsearch

* Beanstalkd
  * sudo apt-get install beanstalkd
  * composer require pda/pheanstalk:dev-master

* Supervisor
  * sudo apt-get install supervisor
  * supervisor.conf is in __doc__

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

- if supervisor error
  - unix:///var/run/supervisor.sock no such file
  - error: , [Errno 13] Permission denied: file: /usr/lib/python2.7/socket.py line: 224
   - try restarting in sudo mode
   - chmod 766 /var/run/supervisor.sock
