composer self-update
if [ -d cli-project ]
then
    cd cli-project
    composer update
    cd ..
else
    composer create-project aura/cli-project --prefer-dist --stability=dev
    # cd cli-project
    # composer require phpunit/phpunit=3.9.*@dev
    # cd ..
fi

rm -rf cli-project/vendor/aura/cli-kernel/*
cp -r autoload.php  cli-project/vendor/aura/cli-kernel/
cp -r composer.json cli-project/vendor/aura/cli-kernel/
cp -r config        cli-project/vendor/aura/cli-kernel/
cp -r README.md     cli-project/vendor/aura/cli-kernel/
cp -r src           cli-project/vendor/aura/cli-kernel/
cp -r tests         cli-project/vendor/aura/cli-kernel/
cd cli-project/vendor/aura/cli-kernel/tests
# ../../../../vendor/bin/phpunit
phpunit
status=$?
cd ../../../..
exit $status
