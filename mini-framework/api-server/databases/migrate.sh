#!/bin/sh

docker exec -it cm-api-server sh -c "php /var/www/api-server/databases/db-init.php"

read -p 'Нажмите Enter для продолжения...'