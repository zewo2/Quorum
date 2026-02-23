# Quorum Campus Management

Quorum is a campus management platform built with Laravel 12 and Livewire. It supports role based dashboards for administrators, teachers, and students, with modules for courses, subjects, enrollments, attendance, scheduling, exams, and profile management.

## Quick Start

Run these commands from the project root for a first local run.

```bash
composer run setup
php artisan serve
php artisan queue:work
npm run dev
```

Open `http://127.0.0.1:8000` after the services are up.

## Core Stack

1. PHP 8.2
2. Laravel 12
3. Livewire 3
4. Laravel Fortify for authentication
5. MySQL
6. Vite and TailwindCSS 4
7. Laravel Queue (database driver)

## Main Features

1. Authentication and role based access control
2. Admin CRUD for users, courses, subjects, enrollments, teacher assignments, rooms, timetables, and exams
3. Teacher dashboard with classes, schedule, and attendance management
4. Student dashboard with subjects, schedule, attendance, grades, and exams
5. Genetic Algorithm timetable generation
6. Queue based GA execution to avoid request timeouts
7. REST endpoints for course, subject, and department data
8. n8n chatbot widget integration on frontend pages

## Project Structure

1. Application logic: `app`
2. Routes: `routes/web.php` and `routes/api.php`
3. Blade views: `resources/views`
4. Frontend assets: `resources/css`, `resources/js`
5. Public chatbot assets: `public/chatbot`
6. Database schema and seeders: `database/migrations`, `database/seeders`

## Setup

### 1) Install dependencies

```bash
composer install
npm install
```

### 2) Configure environment

```bash
cp .env.example .env
php artisan key:generate
```

Set your database credentials in `.env`.

### 3) Run migrations

```bash
php artisan migrate
```

Optional seeding:

```bash
php artisan db:seed
```

### 4) Build or run frontend assets

Development:

```bash
npm run dev
```

Production build:

```bash
npm run build
```

## Running the Application

### Option A: One command for local development

```bash
composer run dev
```

This starts:

1. Laravel server
2. Queue listener
3. Vite dev server

### Option B: Separate terminals

Terminal 1:

```bash
php artisan serve
```

Terminal 2:

```bash
php artisan queue:work
```

Terminal 3:

```bash
npm run dev
```

## Queue Requirement for GA Timetable Generation

GA generation is asynchronous and uses queues. This prevents HTTP timeout issues for heavier filter combinations.

If queue workers are not running, GA runs will remain in `queued` state.

Minimum requirement during development:

```bash
php artisan queue:work
```

Relevant persistence tables:

1. `jobs`
2. `failed_jobs`
3. `ga_runs`

## API Endpoints

Base path: `/api`

1. `GET /api/courses`
2. `GET /api/courses/{id}`
3. `GET /api/subjects`
4. `GET /api/subjects/{id}`
5. `GET /api/departments`
6. `GET /api/departments/{id}`

## n8n Chatbot Integration

The chatbot widget is integrated in the frontend home view and loads assets from `public/chatbot`.

Expected files:

1. `public/chatbot/n8n-chat-widget.js`
2. `public/chatbot/n8n-chat-widget.css`
3. `public/chatbot/scripts.js`

If the chatbot is not visible:

1. Confirm assets exist under `public/chatbot`
2. Confirm endpoint URL in the home view script configuration
3. Check browser console for loading errors

## Testing

Run test suite:

```bash
php artisan test
```

## Common Issues and Fixes

### GA stuck in queued

Cause: queue worker is not running.

Fix:

```bash
php artisan queue:work
```

### API routes returning 404

Cause: API routes not loaded or incorrect URL prefix usage.

Fix:

1. Confirm API routes are registered in bootstrap routing config
2. Use `/api/...` endpoints

### CSS conflicts on frontend

Cause: importing broad global styles from third party widget assets.

Fix:

1. Include only scoped widget CSS
2. Avoid loading global reset styles in frontend layout

## Deployment Notes

For production, ensure:

1. Queue worker process manager is configured (Supervisor, systemd, or equivalent)
2. `php artisan config:cache` and `php artisan route:cache` are used after deployment
3. Node assets are built with `npm run build`
4. Environment variables for DB, mail, and queue are correctly configured

## License

This project is developed for academic and institutional use. It may not be used for any commercial means.
