FROM ubuntu:18.04

MAINTAINER Hyunseok Jung <hyunseokjung163@gmail.com>

# JAVA 11 tjfcl

#system package update
RUN apt-get update && apt-get upgrade -y

RUN apt-get install -y apt-transport-https curl gnupg
#openjdk-11 이 있는 지 확인 후
RUN apt-cache search openjdk
#openjdk-11 설치
RUN apt-get install -y openjdk-11-jre openjdk-11-jdk  

# JAVA_HOME 설정
#RUN cat >> /etc/environment << EOF\
#    JAVA_HOME=/usr/lib/jvm/java-11-openjdk-amd64/\
#    EOF

#Elastic Search 설치
RUN curl -fsSL https://artifacts.elastic.co/GPG-KEY-elasticsearch | apt-key add -

#RUN cat > /etc/apt/sources.list.d/elastic-7.x.list << EOF\
#    deb https://artifacts.elastic.co/packages/7.x/apt stable main\
#    EOF

RUN apt-get update && apt-get install -y elasticsearch

#Elasticsearch 설정

RUN export ES_HOST=$(ifconfig | sed -En 's/127.0.0.1//;s/.*inet (addr:)?(([0-9]*\.){3}[0-9]*).*/\2/p') && echo $ES_HOST

RUN cat >> /etc/elasticsearch/elasticsearch.yml << EOF
#####################
# add custom config #
RUN cluster.name: ttc-shop-search
RUN node.name: ttc-shop-node-1
