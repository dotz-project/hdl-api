#!/bin/bash
sudo docker-compose down; sudo rm storage/rabbitmq/* -Rf; sudo docker-compose up -d --force-recreate; sleep 15; sudo docker-compose exec php up.sh