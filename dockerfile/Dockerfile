FROM gcr.io/ttc-team-03/wordpress:latest
MAINTAINER Hyunseok Jung<hyunseokjung163@gmail.com>
COPY --chown=www-data:www-data . .
RUN rm -rf Dockerfile
EXPOSE 80
CMD ["apache2-foreground"]