FROM ubuntu:18.04
MAINTAINER Hyunseok Jung <hyunseokjung163@gmail.com>
## 환경 변수 설정
ENV INSTALL_DIR="/var/www/wordpress"

# DB 정보
ENV DB_HOST=10.23.64.3   
ENV DB_USER=ttc       
ENV DB_NAME=wordpress   
ENV DB_PASS=TTC@2020sk    
ENV ES_HOST=10.178.0.49 

# SITE 기본 정보
ENV _TITLE_="TTC+SHOP"
ENV _NAME_="ttc"
ENV _PASS_="TTC@2020sk"
ENV _EMAIL_="ttc@sk-ttc.com"

RUN apt-get -y update
RUN apt-get  install -y unzip apt-transport-https sendmail
# Apache2 설치
RUN apt-get install -y apache2
RUN a2enmod rewrite
RUN service apache2 start 

# RUN usermod -a -G www-data $USER

# PHP 7.4 설치
RUN apt-get install -y software-properties-common language-pack-en-base
#RUN LC_ALL=en_US.UTF-8 add-apt-repository ppa:ondrej/php

#RUN timedatectl set-timezone 'Asia/Seoul'
#RUN apt-get install -y php7.4*

# DATABASE 설정
RUN apt-get install -y mariadb-client curl

# Wordpress 설치
RUN curl -fsSL http://wordpress.org/latest.tar.gz | tar zxv

WORKDIR /etc/apache2/sites-enabled
RUN sed -i "s/\/var\/www\/html/\/var\/www\/wordpress/g" 000-default.conf
WORKDIR /var/www/wordpress
#RUN mv wp-config-sample.php wp-config.php