## Chivito Backend (Laravel)

## Local Development

### Prerequisites
- PHP 8.1+
- Composer
- A local database (MySQL or SQLite)

### Setup
1) Install PHP dependencies
```bash
composer install    
```

2) Configure environment
- Copy `.env.example` to `.env`
- Update DB credentials as needed

3) Generate app key
```bash
php artisan key:generate
```

4) Run migrations
```bash
php artisan migrate
```

5) Link storage (for uploaded images)
```bash
php artisan storage:link
```

6) Start the API server
```bash
php artisan serve --port=8002
```

The API will be available at `http://127.0.0.1:8002/api`.

### Notes
- If you change the API port or host, update the frontend env at `chivito-frontend/chivito-frontend/.env.local`.
- File uploads are stored in `storage/app/public` and exposed via `/storage` after running `storage:link`.

### Troubleshooting
- `SQLSTATE[HY000]`: check your database credentials in `.env`.
- `No application encryption key`: run `php artisan key:generate`.
- Missing images: run `php artisan storage:link` and verify `APP_URL`.
