RUN apt update && \
    apt upgrade -y && \
    apt install -y git \

COPY . /var/www/gantt

RUN chmod 0755 /var/www/html/etc/entrypoint.sh
RUN chmod +x /var/www/html/etc/entrypoint.sh
ENTRYPOINT /var/www/html/etc/entrypoint.sh

VOLUME /var/www/html
WORKDIR /var/www/html