# Laravel Dev Suites

**Laravel Dev Suites** is a collection of Laravel utilities, traits, interfaces, and services designed to streamline and enhance your daily development tasks. This repository provides a set of tools to simplify common operations and improve the efficiency of your Laravel projects.

## Features

- **Clone Operation**: Easily duplicate your models and their relationships.
- **Cancel Operation**: Simplify the process of handling cancellation operations.

## Installation

You can install **Laravel Dev Suites** in two ways: **manually** by copying files or **using Composer**.

### Option 1: Manual Installation (Copy-Paste)

1. **Download or Clone the Repository**

   Download the repository as a ZIP file or clone it using Git:

   ```bash
   git clone https://github.com/jeriatno/laravel-devsuites.git
   ```

2. **Copy the Files**

   Copy the relevant files from the downloaded or cloned repository into the appropriate directories of your Laravel project (e.g., app/Traits, app/Services, etc.).

3. **Include in Your Project**

   Add the necessary use statements in your models, controllers, or other classes where you want to utilize the tools.


### Option 2: Composer Installation

1. **Require the Package**

   Run the following command to add the package to your Laravel project:

   ```bash
   composer require jeriatno/laravel-devsuites
   ```

2. **Publish the Package (If Necessary)**

   If the package requires publishing configuration files or assets, you can do so using:

   ```bash
   php artisan vendor:publish --provider="DevsuitesServiceProvider"
   ```

## Usage

### Clone Operation

For detailed instructions on how to use the Clone Operation trait, refer to the [Clone Operation Documentation](docs/clone-operation.md).

### Cancel Operation

For detailed instructions on how to use the Cancel Operation trait, refer to the [Cancel Operation Documentation](docs/cancel-operation.md).

## Contributing

If you want to contribute to this repository, please follow these guidelines:

1. Fork the repository.
2. Create a new branch for your changes.
3. Make your changes and test them.
4. Submit a pull request with a clear description of your changes.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.


