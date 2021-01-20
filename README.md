# Picture Similarity API

Microservice to provide similar product ids for given product via API. Data is created by MachineLearning process in https://gitlab.marmalade.de/makaira/picture_similarity

- API get Reco by a PRODUCT_ID: `<SERVER>/api/<TYPE>/<SHOP_ID>/<PRODUCT_ID>` <br>
  This api returns an JSON-Array of similar IDs. <br>
  Please note that <TYPE> is optional.
- API get Reco by multiple PRODUCT_IDs: `<SERVER>/api/<TYPE>/<SHOP_ID>/products/<PRODUCT_IDs>` <br>
  This api returns an array contains JSON-Arrays of similar IDs. <br>
  Please note that <TYPE> is optional and <PRODUCT_IDs> is a string contains product ids separated by ',' Ex: 1,2,3.
- Protected by BasicAuth
- API User: `makaira`
- API Password: `test` (development) or defined in ENV `API_PASSWORD`

#Note about githooks that will be run before committing code
I added a package named grumphp, this package will help us to add some pre-commit githooks so that we can maintain our code quality(more detail about the package: https://github.com/phpro/grumphp). <br>
These 2 following code validations need to be bypassed in order to commit code:
- The Phpunit Bridge task will run your unit tests thanks to the Symfony Phpunit Bridge.<br>
  More detail: https://github.com/phpro/grumphp/blob/master/doc/tasks/phpunitbridge.md
- The PHPLint task will check your source files for syntax errors.<br>
  More detail: https://github.com/phpro/grumphp/blob/master/doc/tasks/phplint.md

## Local Dev Setup

0. Copy `.env.local.example` to `.env.local`
1. `composer install`
2. Setup DB and set `DATABASE_URL` (see example in .env)
3. Migrate DB with `bin/console doctrine:migrations:migrate`
4. Run local dev server with `symfony server:start`

## Production Setup

0. Setup Server with ansible (see directory ansible)
1. Set environment in apache (`/etc/apache2/sites-available`)
```
SetEnv DATABASE_URL "mysql://YOUR_DB_STRING"
SetEnv APP_ENV "prod"
SetEnv APP_SECRET "THESECRET"
```
2. Set env variable for CLI (/etc/environment)
```
DATABASE_URL="mysql://YOUR_DB_STRING"
APP_ENV="prod"
APP_SECRET="THESECRET"
```
3. Set api user and password in .htpaswd file
2. Upload release to server (e.g. with rundeck)
3. Migrate DB with `bin/console doctrine:migrations:migrate`
3. Import Pingdom Healthcheck Fixtures with `bin/console doctrine:fixtures:load`
