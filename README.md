# meraki-auth

Authentication package for the MerakiLabs ecosystem.

## Installation

```bash
composer require merakilab/meraki-auth
```

Publish config and run migrations:

```bash
php artisan vendor:publish --tag=meraki-auth-config
php artisan vendor:publish --tag=meraki-auth-migrations
php artisan migrate
```

## Theming

All UI styling is driven by CSS custom properties. To change the theme, publish the config and edit `config/meraki-auth.php` — **no need to publish views**.

```php
// config/meraki-auth.php
'ui' => [
    'theme' => [
        'bg'           => '#FDFDFC',   // page background (light)
        'bg_dark'      => '#0a0a0a',   // page background (dark)
        'text'         => '#1b1b18',   // body text (light)
        'text_dark'    => '#EDEDEC',   // body text (dark)
        'primary'      => '#6c63ff',   // buttons, links (light)
        'primary_dark' => '#8b85ff',   // buttons, links (dark)
        'border'       => '#e3e3e0',   // input/card borders (light)
        'border_dark'  => '#3E3E3A',   // input/card borders (dark)
        'radius'       => '0.5rem',    // border-radius for all elements
        'font'         => '"Instrument Sans", sans-serif',
    ],
],
```

Changing any value here updates all views instantly — buttons, inputs, and cards all read from the same CSS variables.

## Blade Components

The package ships five reusable anonymous Blade components under the `meraki-auth` namespace:

| Tag | Props | Description |
|---|---|---|
| `<x-meraki-auth::input>` | `type`, `name`, `error` | Text input with optional error state |
| `<x-meraki-auth::button>` | `variant` (primary/secondary), `loading` | Submit/action button |
| `<x-meraki-auth::card>` | `title` | Card wrapper with shadow |
| `<x-meraki-auth::alert>` | `type` (success/error/warning/info) | Inline alert message |
| `<x-meraki-auth::form-field>` | `label`, `for`, `error` | Field wrapper with label and error |

All components accept `$attributes` (extra classes/attributes are merged).

### Example

```blade
<x-meraki-auth::card title="Sign in">
    <x-meraki-auth::form-field label="Email" for="email" :error="$errors->first('email')">
        <x-meraki-auth::input id="email" type="email" name="email" :error="$errors->first('email')" />
    </x-meraki-auth::form-field>
    <x-meraki-auth::button type="submit">Sign in</x-meraki-auth::button>
</x-meraki-auth::card>
```

## Overriding Views

To customise individual views, publish them to your application:

```bash
php artisan vendor:publish --tag=meraki-auth-views
```

Views are published to `resources/views/vendor/meraki-auth/`. You can edit any file there without touching the package. Published views still inherit CSS variables from config.

## Admin Panel

A basic admin panel is available at `/meraki-admin/users` (requires authentication). It lists all registered users with pagination.

## Routes

All routes are loaded under the `web` middleware. The admin routes require `auth`.

| Method | URI | Name |
|---|---|---|
| GET | `/login` | `login` |
| POST | `/login` | — |
| GET | `/register` | `register` |
| POST | `/register` | — |
| GET | `/forgot-password` | `password.request` |
| POST | `/forgot-password` | `password.email` |
| GET | `/reset-password/{token}` | `password.reset` |
| POST | `/reset-password` | `password.update` |
| GET | `/verify-email` | `verification.notice` |
| GET | `/verify-email/{id}/{hash}` | `verification.verify` |
| POST | `/email/verification-notification` | `verification.send` |
| POST | `/logout` | `logout` |
| GET | `/meraki-admin/users` | `meraki.admin.users.index` |
