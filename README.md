# What is it?

An blank boilerplate plugin, structured a bit more like a big-boy project; namespaces, autoloader, a (basic) renderer, Exceptions.

Ready for composer, and with webpack added in and configured to generate separate js & css for the frontend and admin areas.

An example shortcode, and `wp-json` endpoint have been added.

# Getting it ready to run

1. Clone into your `wp-content/plugins/` folder
2. Run `composer install`
3. Run `npm install`
4. Build the frontend bits with `npx webpack`

>[!IMPORTANT]
>This requires both ACF and WPML to work

# Tests

Running the tests
```bash
./vendor/bin/phpunit tests/
./vendor/bin/phpunit tests/phpunit/Utils/FunctionsTest.php
```

# Development environment: 

```bash
# Start container
docker-compose up -d

# Start (with view on the build logs)
docker-compose up --build

# Stop container 
docker-compose down -v

# Start a shell 
docker exec -it gcms-cloudflareonion bash

# Complete the creation of your environment (if it doesn't happen automagically)
docker exec -it gcms-cloudflareonion "/docker-entrypoint.sh"

# Running tests
composer run-tests --working-dir=./wp-content/plugins/
```

Visit: http://127.0.0.1:8081


# Doc(s)
- [ Manipulate the plugin via Terminus ](https://pernod-ricard.atlassian.net/wiki/spaces/IR/pages/30078019394/03_Terminus+Important+commands#Plugin-management-via-Terminus)




----


### DEV

In your local wordpress plugins folder, symlink the plugin
```bash
ln -sf /My-Versions/cloudflareonion global-plugin-cfo

# Inside the env if using lando !
ln -s /My-Versions/cloudflareonion /app/web/wp-content/plugins/prcfx 
```

# TODO 

- Removes dev files at release (docker, dev, ...)


=======
### Commands

This plugin creates multiple commands to be used through WP CLI

```bash
# Purge by prefix
wp cfo purgeprefix '/en/prefix/'
# Purge all markets
wp cfo purgemarkets

# Inside the env if using lando !
lando wp cfo ####
```
