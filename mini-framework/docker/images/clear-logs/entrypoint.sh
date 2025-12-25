#!/bin/sh

if test -f /var/log/php/error.log; then
    rm /var/log/php/error.log
fi

if test -f /var/log/nginx/error.log; then
    rm /var/log/nginx/error.log
fi

if test -f /var/log/nginx/access.log; then
    rm /var/log/nginx/access.log
fi
