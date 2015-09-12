#!/bin/bash

source config/environment.sh

echo "Poistetaan tietokantataulut..."

ssh $USERNAME@lakka.kapsi.fi "
cd htdocs/$PROJECT_FOLDER/sql
psql < drop_tables.sql
exit"

echo "Valmis!"
