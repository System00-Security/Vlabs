#!/bin/bash

#XSSLAB
#This Project is created and maintained by System00 Security under MIT License.
#Copying this project without permission is illegal.
#Contact: project@system00sec.org



echo '''

   _  __        __        __ 
  | |/_/__ ___ / /  ___ _/ / 
 _>  <(_-<(_-</ /__/ _ `/ _ \
/_/|_/___/___/____/\_,_/_.__/
                              [System00 Security]
'''

backup_dir=".xsslab_backup"

if [[ "$1" == "start" ]]; then
    if [[ ! -d "$backup_dir" ]]; then
        mkdir "$backup_dir"
        echo "[+] Enviroment ready"
    else
        echo "[!] Enviroment already ready"
    fi
    for file in *.php *.json; do
        if [[ -f "$file" ]]; then
            cp "$file" "$backup_dir/.$file" &> /dev/null
        fi
    done
    echo "[+] Starting server"
    php -S localhost:8080
elif [[ "$1" == "reset" ]]; then
    if [[ -d "$backup_dir" ]]; then
        #replace all files with backup
        for file in *.php *.json; do
            if [[ -f "$file" ]]; then
                cp "$backup_dir/.$file" "$file" &> /dev/null
            fi
        done
        rm -rf "$backup_dir"
        echo "[+] Reset complete"
    else
        echo "[!] Enviroment not ready"
    fi
elif [[ "$1" == "stop" ]]; then
    echo "[+] Stopping server"
    pkill php
else
    echo "Usage: ./xsslab.sh [start|stop|reset]"
fi

    

