#!/bin/sh

cd ..
./yii migrate/up --migrationPath=@vendor/dektrium/yii2-user/migrations
