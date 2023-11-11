FROM nginx:stable-alpine3.17

WORKDIR /etc/nginx/conf.d

RUN rm -f ./default.conf

COPY nginx/default.conf .

WORKDIR /var/www/html

COPY src .

