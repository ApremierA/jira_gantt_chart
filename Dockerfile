COPY . /var/www/gantt

RUN chmod 0755 /var/www/html/etc/entrypoint.sh
ENTRYPOINT /var/www/html/etc/entrypoint.sh

VOLUME /var/www/html
WORKDIR /var/www/html