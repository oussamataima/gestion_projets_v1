# Gestion projets
- Clone the repo

```bash
git clone https://github.com/oussamataima/gestion_projets_v1.git

&&

cd livewire-crud
```

- Install composer dependencies

```bash
composer install
```

- Install npm dependencies

```bash

npm install

```

- Create a copy of your .env file

```bash

cp .env.example .env

```

- Generate an app encryption key

```bash

php artisan key:generate

```
- creates a symbolic link in your public directory

```bash

php artisan storage:link

```

- Create an empty database for our application

- In the .env file, add database information to allow Laravel to connect to the database

- Migrate the database

```bash

php artisan migrate

```

- Seed the database

```bash

php artisan db:seed

```

- Run the development server (Ctrl+C to close)

```bash
npm run dev
php artisan serve

```

- Visit [http://127.0.0.1:8000/](http://127.0.0.1:8000/) in your browser
