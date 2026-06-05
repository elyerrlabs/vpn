# 📋 Changelog

All notable changes to this project will be documented in this file.

---

# [v1.0.1] - 2024-06-17

## ✨ Features

### 🖥️ Server Management

Added server administration support for users and administrators.

#### Included

* Create servers
* Update servers
* Delete servers
* List servers
* Plan validation for servers (users only, administrators excluded)

#### Access Zones

* Added a dedicated server management area for administrators
* Added a separate privileged management area for users
* Isolated administrator and user server management interfaces

---

### 🔐 WireGuard VPN Management

Added WireGuard VPN management support for users and administrators.

#### Included

* Create VPN instances
* Update VPN instances
* Delete VPN instances
* List VPN instances
* Plan validation for servers (users only, administrators excluded)

#### Access Zones

* Added a dedicated WireGuard management area for administrators
* Added a separate privileged WireGuard area for users
* Isolated administrator and user VPN management interfaces

---

### 🔗 Peer Management

Added WireGuard peer management support.

#### Included

* Create peers
* Delete peers
* List peers
* Plan validation for all users, including administrators

---

### 🛡️ Scopes & Permissions

Updated authentication scopes for API and web support.

#### Changes

* Added support for:

  * API scopes
  * Web scopes
  * Public scopes

* `commerce:vpn:basic` is now the default scope for:

  * API keys
  * Web authentication
  * Public user access

---
