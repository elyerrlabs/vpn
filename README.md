# 🛡️ VPN Module — OAuth2 Passport Server Extension

This project is a **module for the OAuth2 Passport Server**, designed to provide a modern, flexible, and distributed VPN infrastructure.

The VPN module acts as a **central management server**, responsible for:

- Generating and managing credentials.
- Orchestrating VPN nodes.
- Managing secure client-server connections.
- Providing an API for integration with external applications.

---

## 🚀 Main Features

- 🔐 Native OAuth2 (Passport Server) integration.
- 🌐 Protocol support:
  - WireGuard VPN  
  - SOCKS5 Proxy  
  - HTTPS Proxy (roadmap)
- 🧩 Multi-node architecture:
  - The system can run multiple VPN servers.
  - Users can run their own nodes and optionally share them.
- 🖥️ Cross-platform clients:
  - Browsers
  - Desktop applications (via API)
- 🔄 Centralized management:
  - Automatic subnet generation
  - Dynamic port assignment
  - Gateway control
  - Remote mounting of WireGuard interfaces
- 🔑 Server-to-server authentication using cryptographic signing.
- 🛡️ Global and per-module rate limiting.
- 📦 Fully Dockerized infrastructure.

---

## 🧠 Project Philosophy

The goal is not just a traditional VPN, but a **distributed secure connectivity platform**, where:

- The main server manages identities and permissions.
- VPN nodes run the network infrastructure.
- Users can participate as node providers.
- Everything is controlled via well-defined APIs.

This allows for building:

- Distributed private networks.
- Decentralized proxy systems.
- Scalable VPN infrastructure.

---

## 🧩 Architecture (summary)

- **OAuth2 Passport Server**  
  Core authentication and authorization.

- **VPN Manager (module)**  
  Generates credentials, subnets, and coordinates nodes.

- **VPN Core (on servers)**  
  Runs WireGuard / proxies and validates signed requests from the manager.

Manager-to-core communication is via **gRPC + signed HTTPS verification**.

---

## 🎯 Long-Term Goal

Create an open-source platform that enables:

- Distributed VPN networks.
- Shared proxy infrastructure.
- Custom clients (desktop / browser).
- Users running their own nodes.
- Centralized management with cryptographic security.

Not just a VPN — a **decentralized connectivity infrastructure**.
