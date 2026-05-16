<div align="center">

<a href="https://dlabsit.nl">
  <picture>
    <source media="(prefers-color-scheme: dark)" srcset=".github/assets/logo-dark.svg">
    <img alt="d-labs it" src=".github/assets/logo-light.svg" width="220">
  </picture>
</a>

# Core for Magento 2

**Shared admin UI, module registry, and compatibility tooling for every Dlabsit Magento 2 extension.**
Install this once. Every other Dlabsit module depends on it.

[![Latest Version](https://img.shields.io/packagist/v/dlabsit/module-core?style=flat-square)](https://packagist.org/packages/dlabsit/module-core)
[![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=flat-square&logo=php&logoColor=white)](composer.json)
[![Magento](https://img.shields.io/badge/Magento-2.4.7%20%7C%202.4.8-EE672F?style=flat-square&logo=magento&logoColor=white)](https://github.com/magento/magento2)
[![License: OSL-3.0](https://img.shields.io/badge/license-OSL--3.0-blue.svg?style=flat-square)](LICENSE.md)
[![Status: stable](https://img.shields.io/badge/status-stable-green?style=flat-square)](#status)

</div>

---

## Table of contents

- [What it does](#what-it-does)
- [How it works](#how-it-works)
- [Installation](#installation)
- [The Dlabsit admin menu](#the-dlabsit-admin-menu)
- [Registering a module](#registering-a-module)
- [Architecture](#architecture)
- [Compatibility helper](#compatibility-helper)
- [License validation](#license-validation)
- [Requirements](#requirements)
- [Contributing](#contributing)
- [License](#license)

---

## What it does

Every Dlabsit Magento 2 extension shares the same admin chrome: a top-level menu, a dashboard that lists installed modules with their compatibility status, an about page, and a support page. Putting all of that in one base module means each extension stays focused on its own feature and never duplicates branding code.

Beyond UI, Core also provides:

- A **module registry** that every other Dlabsit module hooks into via `di.xml`.
- A **compatibility helper** that detects the Magento edition (Open Source / Commerce / Cloud), PHP version, and Magento version.
- A **license validator contract** for paid modules (with a no-op default for free modules).
- A **dashboard table** that renders each registered module with PHP/Magento compatibility colour-coded.

## How it works

```
   Dlabsit_XmlFeed      Dlabsit_*Other*       Dlabsit_*Future*
        │                     │                      │
        └────────┬────────────┴──────────┬───────────┘
                 │                       │
                 ▼                       ▼
        ┌────────────────────────────────────────┐
        │            Dlabsit_Core                │
        │  ┌────────────────────────────────┐    │
        │  │  ModuleRegistry                │    │
        │  │  (modules register via di.xml) │    │
        │  └────────────────────────────────┘    │
        │  ┌────────────────────────────────┐    │
        │  │  Admin menu + Dashboard +      │    │
        │  │  About + Support pages         │    │
        │  └────────────────────────────────┘    │
        │  ┌────────────────────────────────┐    │
        │  │  Compatibility helper          │    │
        │  │  + LicenseValidatorInterface   │    │
        │  └────────────────────────────────┘    │
        └────────────────────────────────────────┘
```

Each Dlabsit module ships its own `etc/di.xml` snippet that adds a single entry to `Dlabsit\Core\Model\ModuleRegistry`. The dashboard reads from that registry, calls the compatibility helper for each entry, and renders the table.

## Installation

```bash
composer require dlabsit/module-core
bin/magento module:enable Dlabsit_Core
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento cache:flush
```

That's all that is required. The module installs no database tables, no cron jobs, and no public routes.

## The Dlabsit admin menu

After install, a top-level **Dlabsit** menu appears in the admin sidebar with three entries:

| Entry | What it shows |
|---|---|
| **Dashboard** | Table of every installed Dlabsit module with version, PHP/Magento compatibility, license status, and quick links to config / repo / docs |
| **About** | Company info, contact links, and module inventory |
| **Support** | Contact links, system summary, and one-click report to attach to a support ticket |

The icon in the collapsed sidebar is a **DL** monogram that matches the dashboard logo.

## Registering a module

Other Dlabsit modules register themselves in their own `etc/di.xml`:

```xml
<type name="Dlabsit\Core\Model\ModuleRegistry">
    <arguments>
        <argument name="modules" xsi:type="array">
            <item name="myfeature" xsi:type="array">
                <item name="code" xsi:type="string">myfeature</item>
                <item name="name" xsi:type="string">My Feature</item>
                <item name="description" xsi:type="string">Short description shown on the dashboard.</item>
                <item name="composer_name" xsi:type="string">dlabsit/module-myfeature</item>
                <item name="license_tier" xsi:type="string">free</item>
                <item name="min_php_version" xsi:type="string">8.2</item>
                <item name="min_magento_version" xsi:type="string">2.4.7</item>
                <item name="repository_url" xsi:type="string">https://github.com/dlabsit/m2-myfeature</item>
                <item name="documentation_url" xsi:type="string">https://github.com/dlabsit/m2-myfeature#readme</item>
                <item name="config_section" xsi:type="string">myfeature</item>
            </item>
        </argument>
    </arguments>
</type>
```

The `version` is **not** in this list. Core resolves it automatically from Composer metadata so the dashboard always reflects the installed package.

### License tiers

| Tier | Meaning |
|---|---|
| `free` | Always free. No license check. |
| `free_os_paid_commerce` | Free on Magento Open Source, license required on Adobe Commerce. |
| `paid` | License required on every edition. |

## Architecture

```
Api/                Contracts
  LicenseValidatorInterface    license-key validation contract (v1.1)
  ModuleInfoInterface          one registered module
  ModuleRegistryInterface      collection of installed Dlabsit modules
Block/Adminhtml/    Dashboard, About, Support blocks + system config banner
Controller/Adminhtml/ Dashboard / About / Support controllers
Helper/Compatibility Detect Magento edition + version
Model/
  ModuleInfo                   one registered module (DataObject)
  ModuleRegistry               DI-assembled registry
  License/NullValidator        v1 placeholder, always returns valid
etc/                acl.xml, adminhtml/menu.xml, adminhtml/routes.xml,
                    adminhtml/system.xml, di.xml, module.xml
view/adminhtml/     Layout XML, templates, CSS (admin menu + dashboard)
                    web/images/logo.svg  (dashboard logo, "DL" monogram)
                    web/css/admin-menu.css (collapsed sidebar icon)
```

### Module version resolution

`ModuleInfo::getVersion()` reads the package version from `\Composer\InstalledVersions` at runtime, so:

- No hardcoded version drift in `di.xml`.
- The dashboard always reports the version Composer actually installed.
- Modules can ship a fresh release without touching the registry entry.

An explicit `version` value in `di.xml` is still honoured as a fallback for environments where Composer metadata is not available (manual `app/code/` installs).

## Compatibility helper

`Dlabsit\Core\Helper\Compatibility` exposes:

- `isOpenSource(): bool` / `isCommerce(): bool` / `isCloud(): bool`
- `getMagentoVersion(): string`
- `getPhpVersion(): string`
- `meetsMagentoVersion(string $required): bool`
- `meetsPhpVersion(string $required): bool`

The dashboard uses these to colour each module row red/yellow/green per environment.

## License validation

`Dlabsit\Core\Api\LicenseValidatorInterface` defines:

- `isValid(string $moduleCode): bool` — gate runtime
- `getStatus(string $moduleCode): string` — for display
- `getStatusMessage(string $moduleCode): string` — human-readable

`Dlabsit\Core\Model\License\NullValidator` is the v1 implementation: it always returns valid for the `free` tier and a positive placeholder for `paid`. A future `RemoteValidator` will call the Dlabsit license server when paid modules ship.

## Requirements

- PHP **8.2+**
- Magento **Open Source / Commerce 2.4.7** or **2.4.8**

## Contributing

Bug reports and pull requests are welcome at [GitHub Issues](https://github.com/dlabsit/m2-core/issues).

Project conventions:

- PHP 8.2 syntax, `declare(strict_types=1)`, constructor property promotion.
- Magento Coding Standard, severity 8.
- PHPStan matches the project baseline.

## License

[Open Software License v3.0 (OSL-3.0)](LICENSE.md)
Copyright (c) 2026 Dlabsit.

OSL-3.0 is the same license used by Magento itself. You may install, modify, redistribute, and use this software for any purpose, including commercial use, provided modifications stay under OSL-3.0 and copyright notices remain.

---

<div align="center">

Made by [Dlabsit](https://dlabsit.nl). Extensions built on this Core:
[XML Feed](https://github.com/dlabsit/m2-xml-feed) — more in development.

</div>
