# Bocum Laravel Docker Template

Welcome to **Bocum**, a Laravel-based application. This repository includes a fully Dockerized setup to streamline development and deployment.

## 🚀 Features

- Built with Laravel 11.
- Dockerized with PHP-FPM, MySQL, and Nginx for easy containerized development.

---

## Modify Your `/etc/hosts` File

```
127.0.0.1 bocum.local
```

---

## Generate a Development SSL Certificate

```
./ssl/generate_cert.sh
```

---

## Database Access

```
DB_HOST=database
DB_PORT=3306
DB_DATABASE=bocum
DB_USERNAME=root
DB_PASSWORD=bocum_password
```

---

## 🛠️ build the application

```
docker-compose up --build -d
```

---

## Generate Token ID

```
LC_ALL=C tr -dc 'A-Za-z0-9' </dev/urandom | head -c 64; echo
```

## API Testing

You can test the Honey Samples API using the following curl command:

```bash
curl -X POST http://bocum.local/api/honey-samples \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer ${BOCUM_API_TOKEN_ID}" \
  -d '{"data":{"source":"curl"}}'
```

## Run Database Migrations

After building the application, you need to run the database migrations before accessing the application. Run the following command:

```
docker-compose exec app php artisan migrate
```

---

## Access the Application

https://bocum.local/

---

## 📦 Compiling Tailwind Assets

```
npm run dev     # for dev
npm run build   # for production
```