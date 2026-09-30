#!/usr/bin/env bash

set -eo pipefail
shopt -s nullglob

_mysql() {
  mysql --socket=/var/run/mysqld/mysqld.sock --user=root --password="${MYSQL_ROOT_PASSWORD}" "$@"
}

_main() {
  local db f
  for f in /docker-entrypoint-initdb.d/*.sql.gz; do
    db="$(basename "$f" .sql.gz)"
    echo "Creating DB ${db}"
    echo 'CREATE DATABASE `'${db}'`' | _mysql

    echo "Importing ${f} to ${db}"
    gzip -dc "$f" | _mysql "${db}"
  done
}

_main
