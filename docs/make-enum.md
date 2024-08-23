# Make Enum with Command

This guide explains how to use the `make:enum` command to create enums with empty constants in a Laravel project.

## Prerequisites

- Laravel 8 or later
- PHP 8.1 or later

## Command Syntax

```bash
php artisan make:enum {name} {--values=*}
```

- {name}: The name of the enum class you want to create.
- {--values=*}: A list of constant names for the enum, separated by commas.

## Example Usage

1. Generating a Simple Enum Class
   
To create an enum class called DepositStatus with several constants:
```bash
php artisan make:enum DepositStatus --values=NOT_DEPOSITED,NOT_PAID_OFF,PAID_OFF,SUCCESS
```

This command will generate a file in the app/Enums directory with the following content:

```php 
namespace App\Enums;

enum DepositStatus: string
{
    const NOT_DEPOSITED = '';
    const NOT_PAID_OFF = '';
    const PAID_OFF = '';
    const SUCCESS = '';
}
```

## Notes
- Empty Constants: Constants are created without values (''). You can manually add values later if needed.
- File Location: Enum classes are created in the app/Enums directory.
