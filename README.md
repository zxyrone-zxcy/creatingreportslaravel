# Laravel Product Reports

A Laravel application for creating product reports and viewing product data. The report dashboard summarizes the product records and displays charts for product categories, price ranges, and monthly sales.

## Features

- Product sample data managed with a database seeder.
- Summary metrics for product count, inventory value, average price, and categories.
- Charts for products by category, price range, and monthly sales.
- A report page with a preview of recent products.
- A full product table with inventory status indicators.
- SQLite-compatible monthly sales aggregation.

## Requirements

- PHP and Composer
- Node.js and npm

## Setup

Install the project dependencies and create the environment file if needed:

```bash
composer install
cp .env.example .env
php artisan key:generate
npm install
```

Ensure the `.env` file uses SQLite, then create the local database file if it does not already exist:

```bash
touch database/database.sqlite
```

Create the database tables and add the sample products:

```bash
php artisan migrate --seed
```

Build the frontend assets and start the development server:

```bash
npm run build
php artisan serve
```

Open the URL printed by `php artisan serve`. The home page redirects to the reports dashboard.

To reset the database and reseed it, run `php artisan migrate:fresh --seed`. This command drops all existing tables before recreating them.

## Pages

- `/reports` - summary metrics, charts, and a product preview.
- `/data-table` - the complete product list with stock statuses.

## Assignment Note

This project is only my task assignment for Integrative Programming 1. It was created as an academic activity and is not intended to represent a production-ready product.
