#!/bin/sh

SCRIPT=$(readlink -f "$0")
DIR=$(dirname "$SCRIPT")
../yii migrate/up --migrationPath=@vendor/dektrium/yii2-user/migrations
psql -U wergamers wergamers < $DIR/wergamers.sql
psql -U wergamers wergamers < $DIR/auth.sql
