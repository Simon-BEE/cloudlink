#!/bin/bash

if [ -e .env ]; then
    source .env
else
    echo "Please set up your .env file before starting your environment."
    exit 1
fi


docker-compose build

docker-compose -f docker-compose.yml up -d

sleep 4;

docker exec $CONTAINER_NAME /bin/sh -c 'cd /var/www/ && composer update'

sleep 4;
sleep 4;

docker exec $CONTAINER_NAME /bin/sh -c 'cd /var/www/ && commande/createsql'

echo
echo "#-----------------------------------------------------------"
echo "#"
echo "# Please check your browser at http://localhost:$APP_PORT   "
echo "# Or at $VIRTUAL_HOST (if you are using a webproxy)               "
echo "#"
echo "#-----------------------------------------------------------"
echo

exit 0
