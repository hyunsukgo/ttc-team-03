FROM ubuntu:18.04
MAINTAINER Hyunseok Jung <hyunseokjung163@gmail.com>

RUN apt-get update
RUN apt-get upgrade -y
RUN apt-get install -y nginx
RUN echo "\ndaemon off;" >> /etc/nginx/nginx.conf
RUN chown -R www-data:www-data /var/lib/nginx

# Define mountable directories.
VOLUME ["/etc/nginx/sites-enabled", "/etc/nginx/certs", "/etc/nginx/conf.d", "/var/log/nginx", "/var/www/html"]

# Define working directory.
WORKDIR /etc/nginx

# Define default command.

ADD backend /etc/nginx/sites-available/backend
CMD ["nginx"]
RUN ln -s /etc/nginx/sites-available/backend /etc/nginx/sites-enabled/backend
RUN rm -f /etc/nginx/sites-enabled/default

# Expose ports.
EXPOSE 80
EXPOSE 443