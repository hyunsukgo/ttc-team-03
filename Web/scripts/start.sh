#!/bin/bash
if [ ! -f /var/www/html/wp-config.php ]; then

chown www-data:www-data /var/www/html/wp-config.php
chown www-data:www-data /var/www/html/
chmod 666 /var/www/html/wp-config.php

fi