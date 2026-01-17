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

## Welcome Email + Magic Link

When a user registers, the API sends a welcome email with a magic link. The link:
- redirects to the frontend `/magic` page
- signs the user in automatically

### Required env
Add the frontend URL to your backend `.env`:
```bash
APP_FRONTEND_URL=http://localhost:3000
```

### Mail setup
By default, mail uses the log driver. To send real emails, configure SMTP in `.env`:
```bash
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-user
MAIL_PASSWORD=your-pass
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="hello@yourdomain.com"
MAIL_FROM_NAME="Brega"
```

### Troubleshooting
- `SQLSTATE[HY000]`: check your database credentials in `.env`.
- `No application encryption key`: run `php artisan key:generate`.
- Missing images: run `php artisan storage:link` and verify `APP_URL`.
