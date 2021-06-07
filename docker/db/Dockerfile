FROM 'mysql:5.7'

RUN apt-get update
RUN apt install -y python-pip python3-pip
RUN pip3 install mycli

COPY ./conf/my.cnf /etc/mysql/conf.d/my.cnf
COPY ./init /docker-entrypoint-initdb.d
