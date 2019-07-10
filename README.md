# Picture Similarity API

Microservice to provide similar product ids for given product via API. Data is created by MachineLearning process in https://gitlab.marmalade.de/makaira/picture_similarity

- URL: `<SERVER>/api/<SHOP_ID>/<PRODUCT_ID>`
- returns JSON-Array of similar IDs

- Protected by BasicAuth
- API User: `makaira`
- API Password: `test` (development) or defined in ENV `API_PASSWORD`


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
