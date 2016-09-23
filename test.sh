#!/usr/bin/env bash

suite="local_dev"
behat=vendor/bin/behat
phpunit="phpdbg -qrr bin/phpunit"

for i in "$@"
do
case $i in
    -s=*|--suite=*)
    suite="${i#*=}"
    shift # past argument=value
    ;;
    *)
          # unknown option
    ;;
esac
done

packages=(SIC framework)

for package in ${packages[@]}; do
    echo "testing ${package}"
    cd ${package}
    if [[ "$package" = "framework" ]]
    then
        if [[ "$suite" = "ci" ]]
        then
            ${behat} --suite=${suite} --format=junit --out=${suite}.xml
        else
            ${behat} --suite=${suite} --format=progress
        fi

        if [[ $? -ne 0 ]]
        then
            exit 1
        fi

    else
        ${phpunit} tests/

        if [[ $? -ne 0 ]]
        then
            exit 1
        fi
    fi
    cd ..
done

exit 0