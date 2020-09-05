FROM gcr.io/ttc-team-03/wordpress:latest
MAINTAINER Hyunseok Jung<hyunseokjung163@gmail.com>
ADD ./scripts/start.sh start.sh
COPY . .
RUN rm -rf Dockerfile
RUN chown -R www-data:www-data /var/www/html
EXPOSE 80
CMD ["apache2-foreground"]