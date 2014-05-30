pfe
===

Our Graduation Projet


Initialisation
--------------
Clone and Follow the steps: https://github.com/BodinSamuel/vm_dev

You are done (barely)


Nginx and laravel
-----------------
Nginx may not understand rewrited url, you need to modify the config file

Install emacs, if not installed.

Edit configuration file and set: 

Reload nginx



Error
-----

- if vagrant can't up or up be no connection: reboot pc
- if everypage is 502:
    - sudo tail -f /var/log/nginx/pfe.dev.error.log
        - if can't connect to php-fpm
            - cd /var/run && sudo chmod 666 php5-fpm.sock

