#!/bin/sh

sudo -u postgres dropuser wergamers
sudo -u postgres dropdb wergamers
sudo -u postgres psql -c "create user wergamers password 'wergamers' superuser;"
sudo -u postgres createdb -O wergamers wergamers
