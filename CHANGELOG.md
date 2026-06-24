# 📋 Changelog

All notable changes to this project will be documented in this file.

---

## Unrelease

### Added

- Full compatibility with Elymod App v1.0.2 modular architecture.
- Support for shared host runtime and ecosystem services.
- Native integration with the new Elymod Plug & Play module system.

### Changed

- Migrated the VPN module to the Elymod App v1.0.2 module schema.
- Updated module structure to follow the new modularization standards.
- Modules now rely on services provided by the host application instead of bundling framework-level dependencies.
- Improved interoperability with other Elymod ecosystem modules.

### Removed

- Direct dependency on `laravel/framework`.
- Direct dependency on `elyerr/laravel-runtime`.
- Direct dependency on `elyerr/api-response`.
- Redundant framework packages previously duplicated across modules.

### Benefits

- Reduced installation size and dependency duplication.
- Faster dependency resolution and module installation.
- Cleaner module boundaries and separation of concerns.
- Improved maintainability across the Elymod ecosystem.
- True shared-runtime architecture where common services are provided by the host application.

---

# [v2.0.0]

### Added

- Added compatibility with **OAuth2 Passport Server v7+**.
- Added updated module bootstrap support required by the latest **Elymod ecosystem**.
- Added `build-fix.js` script to properly handle and fix asset compilation for modules.

### Changed

- Updated `composer.json` dependencies to align with the current **Elymod** and **OAuth2 Passport Server v7** ecosystem.
- Updated frontend dependencies in `package.json` to supported versions.
- Adjusted `webpack.mix.js` configuration to match the latest **Laravel Mix module build environment**.
- Updated the module `ServiceProvider` implementation to support **OAuth2 Passport Server v7+** and the latest module loading workflow.
- Improved asset compilation compatibility for fonts, icons, and third-party resources.

---

### Migration Notes

Modules created with older Elymod versions should be updated to ensure compatibility with **OAuth2 Passport Server v7+**:

- Update `composer.json` dependencies.
- Update `package.json` dependencies.
- Adjust `webpack.mix.js` using the latest stub version.
- Replace the module `ServiceProvider` with the version provided in the latest Elymod stubs.
- Ensure `build-fix.js` is included and executed when compiling module assets.
- Rebuild frontend assets after updating dependencies.

---

### Summary

This update maintains **Laravel Mix** support and introduces required fixes for module asset compilation, ensuring full compatibility with **OAuth2 Passport Server v7+** and newer Elymod releases.

---

# [v1.0.1]

## ✨ Features

### 🖥️ Server Management

Added server administration support for users and administrators.

#### Included

- Create servers
- Update servers
- Delete servers
- List servers
- Plan validation for servers (users only, administrators excluded)

#### Access Zones

- Added a dedicated server management area for administrators
- Added a separate privileged management area for users
- Isolated administrator and user server management interfaces

---

### 🔐 WireGuard VPN Management

Added WireGuard VPN management support for users and administrators.

#### Included

- Create VPN instances
- Update VPN instances
- Delete VPN instances
- List VPN instances
- Plan validation for servers (users only, administrators excluded)

#### Access Zones

- Added a dedicated WireGuard management area for administrators
- Added a separate privileged WireGuard area for users
- Isolated administrator and user VPN management interfaces

---

### 🔗 Peer Management

Added WireGuard peer management support.

#### Included

- Create peers
- Delete peers
- List peers
- Plan validation for all users, including administrators

---

### 🛡️ Scopes & Permissions

Updated authentication scopes for API and web support.

#### Changes

- Added support for:
  - API scopes
  - Web scopes
  - Public scopes

- `commerce:vpn:basic` is now the default scope for:
  - API keys
  - Web authentication
  - Public user access

---
