#!/bin/bash
#pecl install grpc
#pecl install protobuf
composer install
yii migrate --migrationPath=vendor/yiisoft/yii2/rbac/migrations/  --interactive=0 
yii migrate  --interactive=0