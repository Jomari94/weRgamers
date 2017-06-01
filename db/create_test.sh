#!/bin/sh

sudo -u postgres dropdb wergamers_test
sudo -u postgres createdb -O wergamers wergamers_test
sudo pg_dump -U wergamers wergamers > wergamers_dump.sql
sudo psql -U wergamers wergamers_test < wergamers_dump.sql
