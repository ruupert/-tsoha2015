#!/bin/bash

source config/environment.sh

echo "Luodaan tietokantataulut..."

ssh -i /home/ruupert/.ssh/id_rsa_kapsi $USERNAME@lakka.kapsi.fi "
cd htdocs/$PROJECT_FOLDER/sql
cat create_tables.sql | psql -1 -f -
exit"

echo "Valmis!"
