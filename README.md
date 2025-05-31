# GetPayIn - Social Media Post Management System

A Laravel-based application for scheduling and managing social media posts across multiple platforms.

## Features

- Multi-platform social media post scheduling
- Post management with draft, scheduled, and published states
- Image upload support
- Platform-specific content validation
- User authentication and authorization
- Real-time post status updates
- Calendar view for scheduled posts

## Requirements

- PHP 8.2 or higher
- Composer
- Node.js & NPM
- MySQL 8.0 or higher
- Git

## Installation

1. Clone the repository:
```bash
git clone https://github.com/yourusername/GetPayIn.git
cd GetPayIn
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install JavaScript dependencies:
```bash
npm install
```

4. Create environment file:
```bash
cp .env.example .env
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Configure your database in `.env`:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=getpayin
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

7. Run migrations:
```bash
php artisan migrate:fresh --seed
```

8. Create storage link:
```bash
php artisan storage:link
```

9. Build assets:
```bash
npm run build
```

## Development

1. Start the development server:
```bash
php artisan serve
```

2. Watch for asset changes:
```bash
npm run dev
```

3. Fix code style:
```bash
./vendor/bin/pint
```

4. Test user:
```bash
email: demo@example.com
password: password
```
## Queue Configuration

The application uses database queue driver for processing scheduled posts. Configure your `.env`:

```
QUEUE_CONNECTION=database
```

Run the queue worker:
```bash
php artisan queue:work --queue=default,posts
```

Run publish queue:
```bash
php artisan posts:process-scheduled
```

Run scheduler:
```bash
php artisan schedule:run
```

## API Documentation

### Authentication

All API routes except login and register require Bearer token authentication.

```bash
# Login
POST /api/login
{
    "email": "user@example.com",
    "password": "password"
}

# Register
POST /api/register
{
    "name": "User Name",
    "email": "user@example.com",
    "password": "password",
    "password_confirmation": "password"
}
```

### Posts

```bash
# Create post
POST /api/posts
{
    "title": "Post Title",
    "content": "Post content",
    "platforms": [1, 2],
    "scheduled_at": "2024-03-22 15:00:00",
    "image": <file>
}

# List posts
GET /api/posts?status=scheduled&date=2024-03-22

# Update post
PUT /api/posts/{id}

# Delete post
DELETE /api/posts/{id}
```

## Security

- API rate limiting is implemented
- File upload validation
- CSRF protection
- XSS protection
- SQL injection prevention

## Error Handling

The application implements comprehensive error handling:

- Validation errors
- Platform-specific errors
- File upload errors
- Queue processing errors

## Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## License

This project is licensed under the MIT License.
