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
curl -X POST http://bocum.local/api/sugarcane-samples \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer ${BOCUM_API_TOKEN}" \
  -d '{
    "avg_brix": 18.5,
    "pol": 16.2,
    "ch_r": 1245,
    "ch_s": 1150,
    "ch_t": 1050,
    "ch_u": 920,
    "ch_v": 850,
    "ch_w": 750,
    "sensor_temp_c": 25.3,
    "model_version": "v1.0",
    "coeff_hash": "abc123def456"
  }'
```

You can test the Prediction API using the following curl command:

```bash
curl -X POST http://bocum.local/api/predictions \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer ${BOCUM_API_TOKEN}" \
  -d '{
    "R": 1385.51,
    "S": 339.31,
    "T": 193.41,
    "U": 119.0,
    "V": 82.75,
    "W": 43.06
  }'
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
yarn run dev     # for dev
yarn run build   # for production
```

## Get your laptop’s LAN IP address

```
ipconfig getifaddr en0
```

## create seeder
```
php artisan make:seeder WeeklyPricesSeeder
```

## run seeder
```
php artisan db:seed
php artisan db:seed --class=WeeklyPricesSeeder
```
