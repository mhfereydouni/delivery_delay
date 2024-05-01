# delivery_delay

## how to use:

cd into the project base.

copy the .env.example into .env

run:
```bash
docker compose -p delivery_delay run --rm composer install
```

this command installed the project dependencies.

then run:
```bash
docker compose -p delivery_delay run --rm artisan key:generate
```

this command generated the application key.

then you can seed the database to have a little data to play around. run:
```bash
docker compose -p delivery_delay run --rm artisan migrate:fresh --seed --force
```

for the final step run:
```bash
docker compose -p delivery_delay run --rm artisan optimize

docker compose -p delivery_delay up -d
```

now you can use application in [localhost](http://localhost:80) 🥳.

you can use Postman file to work with the APIs. the file is here: [postman collection file](./delivery%20delay.postman_collection.json)

You can also use applications like TablePlus to manage the database. Just consider to avoid creating data that puts system in a not allowed state.


## tests

In order to run tests you can run this commands:

```bash
docker compose -p delivery_delay run --rm artisan optimize --env=testing

docker compose -p delivery_delay run --rm artisan test
```


## future improvements
* Upgrade laravel version
* Make API calls more robust. For example, we can add retry mechanism.
* Add GitHub actions for code style and tests.
* Put Database and other credentials in .env and use environment variables in docker compose.
* There are some messages in the app. We can put them in translation files and add support for other languages.
