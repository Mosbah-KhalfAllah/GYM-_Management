# Copilot Instructions for Gym Management System

## Architecture Overview

**Tech Stack:** Laravel 12 + MySQL + Tailwind CSS + Vite

This is a role-based gym management application with three user roles (Admin, Coach, Member) and corresponding dashboards, CRUD operations, and real-time data.

### Key Components & Data Flow

1. **Database (MySQL `gym`):**
   - Core tables: `users`, `workout_programs`, `exercises`, `member_programs`, `classes`, `equipment`, `memberships`, `attendances`
   - Pivot table: `member_programs` (links members to workout programs with status tracking)
   - Foreign keys: Use `member_id` NOT `user_id` for member relations (e.g., `member_programs.member_id`)

2. **Models** (`app/Models/`):
   - `User`: Has roles (admin, coach, member); relations use `member_id` for pivot tables
   - `WorkoutProgram`: Created by coaches; hasMany exercises via `program_id`
   - `MemberProgram`: Junction table with pivot columns (`status`, `current_day`, `completion_percentage`)
   - **Pattern:** Access `$user->activeProgram` via accessor `getActiveProgramAttribute()`, NOT as a loadable relation

3. **Controllers** (`app/Http/Controllers/`):
   - Structure: `Admin/`, `Coach/`, `Member/` namespaces with role-specific logic
   - **Auth check:** Always verify user role matches route access (e.g., coach can only view their own assigned members)
   - **Queries:** Use `whereHas()` with coach_id for filtering coach-specific data

4. **Views** (`resources/views/`):
   - Layouts: `layouts/app.blade.php` (main template with sidebar)
   - Components: `components/form-field.blade.php` (reusable form inputs with validation styling)
   - Pattern: Use `@extends('layouts.app')` for all authenticated pages
   - Validation alerts: Automatically handled by `resources/js/form-validation.js` (changes border color red/green, shows error messages)

## Development Workflow

### Setup & Running

```bash
# Environment setup (XAMPP with MySQL `gym`)
cp .env.example .env
# Edit .env: DB_CONNECTION=mysql, DB_DATABASE=gym, SESSION_DRIVER=file

# Database
php artisan migrate:fresh --seed --force    # Reset + seed with 5 members, 2 coaches, 3 programs
php artisan db:seed                         # Seed without reset

# Dev server
php artisan serve --host=127.0.0.1 --port=8000
npm run dev      # Vite build for Tailwind/JS

# Test accounts (from seeder)
# Admin: admin@gym.com / password
# Coach: coach1@gym.com, coach2@gym.com / password
# Members: member1-5@gym.com / password
```

### Key Conventions

1. **Relations & Foreign Keys:**
   - Member relations: use `member_id` (not `user_id`)
   - Example: `MemberProgram` has `foreign('member_id')->references('id')->on('users')`
   - **Load accessor, not relation:** `$user->activeProgram` (accessor) vs. `$user->programs()->wherePivot('status', 'active')->first()` (when eager loading)

2. **Form Validation:**
   - Use `x-form-field` component (auto-colors fields red on error, green when valid)
   - Server-side: Return errors via `$errors->first('field_name')`
   - Client-side JS: Runs on all forms, validates email, tel, password (min 6 chars), date formats

3. **View Patterns:**
   - List pages: Use `@include('components.generic-list', ['items' => $collection])` for table rendering
   - Create/Edit: Post to `route('admin.resource.store')` or `PUT route('admin.resource.update', $model)`
   - Sidebar: Checks `Auth::user()->role` to show role-specific navigation

4. **Database Seeding:**
   - File: `database/seeders/DatabaseSeeder.php`
   - Creates: 1 admin, 2 coaches, 5 members + realistic data (names, addresses, dates)
   - Generates: 18 exercises (6 per program), 75 exercise logs, class bookings, challenge participants

## Common Patterns & Examples

### Adding a New CRUD Page
1. Create controller in `app/Http/Controllers/{Role}/ResourceController.php`
2. Create views: `resources/views/{role}/resource/{index,create,edit,show}.blade.php`
3. Use `x-form-field` component for form inputs
4. Register routes in `routes/web.php` (check middleware & role prefix)

### Querying Coach-Specific Data
```php
// Get all members assigned to a coach's programs
$coach = Auth::user();
$members = User::whereHas('programs', function ($query) use ($coach) {
    $query->where('coach_id', $coach->id);
})->with(['membership', 'programs'])->paginate(10);
```

### Accessing Active Program (Correct)
```php
// Method 1: Accessor (single access)
$active = $user->activeProgram;

// Method 2: Query (multiple uses or eager load)
$active = $user->programs()->wherePivot('status', 'active')->first();
```

## Error Patterns & Fixes

- **RelationNotFoundException:** Don't load `activeProgram` with `->with()` — it's an accessor, use direct property access or query
- **Foreign Key Mismatch:** Check pivot tables use `member_id`, not `user_id`
- **Session DB Error:** If session table missing, switch to `SESSION_DRIVER=file` in `.env`
- **Missing Validation:** Wrap form fields with `x-form-field` component for automatic styling + JS validation

## Files to Reference

- Architecture: `routes/web.php` (route structure), `app/Models/User.php` (role & relations)
- Seeding: `database/seeders/DatabaseSeeder.php`
- Form validation: `resources/js/form-validation.js`, `resources/views/components/form-field.blade.php`
- Layout: `resources/views/layouts/app.blade.php` (sidebar, auth checks)
