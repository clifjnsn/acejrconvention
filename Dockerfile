FROM debian:stable
MAINTAINER Clifton E Johnson (Clifton_Johnson@Protonmail.com)
LABEL description: "Web system for use in Master Control"
RUN apt-get update && apt-get -y upgrade && apt-get install -y php mariadb-server php-mysql nano && mkdir /var/www/html/fpdf16
COPY *.php /var/www/html/
COPY fpdf16/* /var/www/html/fpdf16/
COPY convention.sql /root
COPY startup.sh /root
RUN chmod -R 755 /var/www/html  && chmod 700 /root/startup.sh
