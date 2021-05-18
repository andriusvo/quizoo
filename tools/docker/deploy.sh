#!/bin/bash

set -e

DEPLOY_NAME="$2"
PROJECT_ROOT="$(dirname $(dirname $(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)))"
echo -e "Triggering ${DEPLOY_NAME} deploy\n\n"

function setPerms {
	mkdir -p $1
	sudo setfacl -R  -m m:rwx -m u:33:rwX -m u:1000:rwX $1
	sudo setfacl -dR -m m:rwx -m u:33:rwX -m u:1000:rwX $1
}

echo -e '\n## Install mage ... '
mkdir -p /tmp/package/tools/mage
cd /tmp/package/tools/mage
composer init -n
composer --no-interaction config github-oauth.github.com 3b6e5f518a2ffb7399c2b8be8629e91f8feb5821
composer --no-interaction require 'andres-montanez/magallanes' '^4.0'

echo -e '\n## Setting up permissions ... '
setPerms "${PROJECT_ROOT}/var"
setPerms "${PROJECT_ROOT}/var/log"

cd ${PROJECT_ROOT}

SYMFONY_ENV=prod /tmp/package/tools/mage/vendor/bin/mage deploy "${DEPLOY_NAME}"
