FROM php:8.2-apache
COPY index.html / var/www/html/

FROM php:8.2-apache 
WORKDIR /var/www/
RUN mkdir project
COPY .project
COPY vhosts.conf /etc/apache2/sites-endabled
RUN /etc/init.d/apache2 restart
EXPOSE 80 