#!/bin/sh
rm /var/www/html/index.html
/etc/init.d/mysql restart
mysql -u root < /root/convention.sql
/etc/init.d/apache2 restart
