#!/bin/bash
if [ ! -e /var/www/html/wp-config.php ]; then

chown -R www-data:www-data /var/www/html/wp-config.php
chown -R www-data:www-data /var/www/html/

fi