# Picture Similarity API

Microservice to provide similar product ids for given product via API. Data is created by MachineLearning process in https://gitlab.marmalade.de/makaira/picture_similarity

## Local Setup

1. `composer install`
2. Setup DB and set `DATABASE_URL` (see example in .env)
3. Migrate DB with `bin/console doctrine:migrations:migrate`
4. Run local dev server with `symfony server:start`

## Production Setup

1. Set `DATABASE_URL` in environment
2. Set random `APP_SECRET` in environment
3. Set `APP_ENV=production` in environment
4. `composer install`
3. Migrate DB with `bin/console doctrine:migrations:migrate`

## Endpoint for similar products

- Protected by BasicAuth
- URL: <SERVER>/api/<SHOP_ID>/<PRODUCT_ID>
- returns JSON-Array of similar IDs