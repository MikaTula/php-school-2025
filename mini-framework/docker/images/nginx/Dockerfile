FROM nginx:1.27-alpine

# alpine based = ubuntu => apk istead apt
RUN apk update && \
  apk add mc && \
  apk add htop 
RUN rm /etc/nginx/conf.d/default.conf
