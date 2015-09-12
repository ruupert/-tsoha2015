#!/bin/bash

source config/environment.sh

ssh -i /usr/home/ruupert/.ssh/id_rsa_kapsi $USERNAME@lakka.kapsi.fi '
tail -f /home/users/ruupert/sites/ruupert.kapsi.fi/log/error.log'
