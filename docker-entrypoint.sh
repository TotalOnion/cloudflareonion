#!/bin/bash

# Delete unwanted files
rm -rf /var/www/html/wp-content/plugins/akismet/
rm -rf /var/www/html/wp-content/plugins/hello.php
rm -rf /var/www/html/wp-content/plugins/index.php

# Start Apache in background
docker-entrypoint.sh apache2-foreground &

# Wait for DB / WordPress
# until wp core is-installed --allow-root; do
#   echo "Waiting for WordPress..."; sleep 3;
# done

# Run setup tasks
wp core install \
  --url="http://localhost:8081" \
  --title="Cloudflare Onion" \
  --admin_user=root \
  --admin_password=password \
  --admin_email=root@hello.com \
  --skip-email \
  --allow-root

wp plugin activate global-cfo --allow-root

wait
