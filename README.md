# Report management

## This project is built with Laravel, Inertiajs, ReactJs, TypeScript and TailwindCss

---

### System requirements:

```bash
PHP >= 8.2
Laravel 10.0
Node >= 16.19.1
```

### Run cli

```bash
composer install
```

```bash
composer dump-autoload
```

```bash
npm install
```

```bash
npm run build
```

Create a `.env` file with the content of `.env.example` file and then update the below info of `.env` file for sending email, eg:

```bash
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=youremail@example.com
MAIL_PASSWORD=******
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="youremail@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

Update below info from `.env` file for api key

```bash
RAPID_API_KEY=your-api-key
RAPID_API_HOST=yh-finance.p.rapidapi.com
```

---

### Run below cli to clear cache

```bash
php artisan config:cache
```

```bash
php artisan route:cache
```

### Now run the project using

```bash
php artisan serve
```

Click on the link showing to your command terminal eg: http://127.0.0.1:8000/

### Alternatively, you can run the project in the Docker

```bash
docker-compose up -d
```

Browse: http://localhost:8080

### To run unit tests

```bash
php artisan test
```

OR

```bash
./vendor/bin/phpunit --testdox
```
