# Changelog

All notable changes to `livewire-toaster` will be documented in this file.

## 2.8.0 - 2025-05-02

### Added

- Tailwind 4 support

## 2.7.0 - 2025-02-25

### Added

- Laravel 12 support

## 2.6.0 - 2024-12-01

### Added

- PHP 8.4 support

## 2.5.0 - 2024-11-25

### Added

- Replacement of similar toasts

## 2.4.0 - 2024-11-24

### Added

- Suppression of duplicate toasts

## 2.3.2 - 2024-11-06

### Fixed

- Use the Livewire relay if redirecting using navigate

## 2.3.1 - 2024-07-21

### Fixed

- Removed lingering #[Override] attribute

## 2.3.0 - 2024-07-20

### Added

- PHP 8.2 support

## 2.2.1 - 2024-04-09

### Fixed

- Prevent error in toast disposal when `$el` is `null`

## 2.2.0 - 2024-03-11

### Added

- Laravel 11 support

### Removed

- Laravel 10 support

## 2.1.0 - 2023-12-13

### Added

- PHP 8.3 support

### Removed

- PHP 8.2 support

## 2.0.3 - 2023-09-28

- Prevent unfinalize usage

## 2.0.2 - 2023-09-23

### Fixed

- Add Octane support by @yehorherasymchuk in [#23](https://github.com/masmerise/livewire-toaster/pull/23)

## 2.0.1 - 2023-09-04

### Fixed

- Dispatch events on the `document` node instead of `window`

## 2.0.0 - 2023-08-24

### Added

- Livewire 3 support

### Removed

- Livewire 2 support

## 1.3.0 - 2023-08-05

### Added

- RTL support

## 1.2.1 - 2023-07-03

### Fixed

- `scope` the `toaster` service in order to support Laravel Octane

## 1.2.0 - 2023-06-13

### Added

- Middle alignment by @aldozumaran in [#9](https://github.com/masmerise/livewire-toaster/pull/9)

## 1.1.2 - 2023-05-08

### Fixed

- Register `ToastableMacros` with the `Redirector`

## 1.1.1 - 2023-04-28

- (Performance) improvements

## 1.1.0 - 2023-04-21

### Added

- Vertically alignable toast container

## 1.0.0 - 2023-02-25

- Stable release
