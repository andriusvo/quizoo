#!/bin/bash

set -e
set -x

PROJECT_ROOT="$(dirname $(dirname $(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)))"

echo "PROJECT ROOT: ${PROJECT_ROOT}"
cd "${PROJECT_ROOT}"

function setPerms {
	mkdir -p $1
	sudo setfacl -R  -m m:rwx -m u:33:rwX -m u:1000:rwX $1
	sudo setfacl -dR -m m:rwx -m u:33:rwX -m u:1000:rwX $1
}

echo -e '\n## Setting up permissions ... '
setPerms "${PROJECT_ROOT}/var/cache"
setPerms "${PROJECT_ROOT}/var/log"
setPerms "${PROJECT_ROOT}/var/sessions"
setPerms "${PROJECT_ROOT}/var/jwt"
setPerms "${PROJECT_ROOT}/var/uploads"

cd "${PROJECT_ROOT}"

rm -rf ./var/cache/*

echo -e '\n## Composer install ... '
composer --no-interaction config -g github-oauth.github.com 3b6e5f518a2ffb7399c2b8be8629e91f8feb5821
time composer --no-interaction install

bin/console cache:clear --env=prod

if [ "${NFQ_PROJECT_INIT:-1}" = '1' ]; then
    echo -e '\n## Generating  JWT token keys... '
    openssl genrsa -passout pass:project -out var/jwt/private.pem -aes256 4096
    openssl rsa -passin pass:project -pubout -in var/jwt/private.pem -out var/jwt/public.pem

    echo -e '\n## DB setup ... '
    bin/console doctrine:database:drop --force --if-exists
    bin/console doctrine:database:create
    bin/console doctrine:migrations:migrate --no-interaction
    bin/console sylius:fixtures:load default --no-interaction


    echo -e '\n## Testing DB setup ... '
    bin/console doctrine:database:drop --force --if-exists --env=test
    bin/console doctrine:database:create --env=test
    bin/console doctrine:schema:update --force --env=test
fi

echo -e '\n## Frontend setup ... '
yarn install
yarn build

sleep 15

echo -e '\n## Clearing doctrines cache ... '
bin/console doctrine:cache:clear-result --env=prod
bin/console doctrine:cache:clear-query --env=prod
bin/console doctrine:cache:clear-metadata --env=prod
