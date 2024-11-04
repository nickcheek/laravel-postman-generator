# Laravel Postman Generator

This package automatically generates a Postman collection from your Laravel API routes. It organizes routes into folders based on their base paths and includes proper headers and request configurations.

## Features

- Automatically generates Postman collections from Laravel routes
- Groups API endpoints by base path
- Converts Laravel route parameters to Postman variables
- Includes default headers for API requests
- Adds request body templates for POST, PUT, and PATCH methods
- Customizable collection name
- Supports Laravel 8+ and PHP 8+

## Installation

You can install the package via composer:

```bash
composer require nickcheek/laravel-postman-generator
```

Laravel will auto-discover the package, so no additional setup is required.

## Usage

### Basic Usage

Generate a collection with default settings:

```bash
php artisan postman:generate
```

This will create `postman-collection.json` in your project root with the default name "Laravel API".

### Custom Output Path

Specify where to save the collection:

```bash
php artisan postman:generate /path/to/my-collection.json
```

### Custom Collection Name

Use the --name option to set a custom collection name:

```bash
php artisan postman:generate --name="My Custom API"
# or use the shorthand
php artisan postman:generate -N "My Custom API"
```

### Combine Options

Set both custom path and name:

```bash
php artisan postman:generate /path/to/collection.json --name="My API Collection"
```

## Generated Collection Features

The generated Postman collection includes:

- Organized folder structure based on route groups
- Pre-configured headers:
    - `Accept: application/json`
    - `Content-Type: application/json`
    - `Authorization: Bearer {{token}}`
- Postman variables for dynamic values
- Request body templates for POST/PUT/PATCH methods
- Converted route parameters (e.g., {id} becomes :id)

## Example

If your Laravel routes look like this:

```php
Route::prefix('api')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    
    Route::prefix('admin')->group(function () {
        Route::get('/settings', [AdminController::class, 'settings']);
    });
});
```

The generated collection will organize them into folders:
- Users
    - GET /api/users
    - POST /api/users
- Admin
    - GET /api/admin/settings

## Requirements

- PHP 8.0 or higher
- Laravel 8.0 or higher

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## Security

If you discover any security related issues, please email nick@nicholascheek.com instead of using the issue tracker.

## Credits

- [Nicholas Cheek](https://github.com/nickcheek)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Support

For support, email nick@nicholascheek.com or create an issue in the GitHub repository.

