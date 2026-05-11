# Dlabsit Core — Magento 2 Base Module

**Required base module for all `Dlabsit_*` Magento 2 extensions.**

Dlabsit_Core provides the shared admin UI, module registry, compatibility
checks, support tooling, and (future) license validation for all Dlabsit
extensions. Install this once; every other Dlabsit module depends on it.

**License:** OSL-3.0 (always free)
**Magento:** 2.4.7 / 2.4.8+
**PHP:** 8.2 / 8.3 / 8.4

---

## Features

- **Top-level "Dlabsit" admin menu** with logo and quick access
- **Dashboard** listing every installed Dlabsit module with compatibility status
  (PHP version OK/fail, Magento version OK/fail, license status)
- **About page** with company info, contact links, and module inventory
- **Support page** with contact links + one-click system report for support tickets
- **Module Registry** — other Dlabsit modules register their metadata via `di.xml`
- **Compatibility helper** — detect Magento edition (Open Source / Commerce / Cloud),
  PHP/Magento versions
- **License placeholder** — infrastructure ready for remote validation in v1.1

---

## Installation

```bash
composer require dlabsit/module-core
bin/magento module:enable Dlabsit_Core
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento cache:flush
```

Or manually copy to `app/code/Dlabsit/Core/` and run the four commands above.

---

## Usage

Navigate to **System → Dlabsit → Dashboard** in your admin.

### For developers: registering a new Dlabsit module with Core

In your module's `etc/di.xml`:

```xml
<type name="Dlabsit\Core\Model\ModuleRegistry">
    <arguments>
        <argument name="modules" xsi:type="array">
            <item name="myfeature" xsi:type="array">
                <item name="code" xsi:type="string">myfeature</item>
                <item name="name" xsi:type="string">My Feature</item>
                <item name="description" xsi:type="string">Short description</item>
                <item name="version" xsi:type="string">1.0.0</item>
                <item name="composer_name" xsi:type="string">dlabsit/module-myfeature</item>
                <item name="license_tier" xsi:type="string">free</item>
                <item name="min_php_version" xsi:type="string">8.2</item>
                <item name="min_magento_version" xsi:type="string">2.4.7</item>
                <item name="repository_url" xsi:type="string">https://github.com/dlabsit/m2-myfeature</item>
                <item name="documentation_url" xsi:type="string">https://github.com/dlabsit/m2-myfeature#readme</item>
            </item>
        </argument>
    </arguments>
</type>
```

License tiers:
- `free` — always free (Core, most feed writers)
- `free_os_paid_commerce` — free on Magento Open Source, paid on Adobe Commerce
- `paid` — always requires a license (marketplace integrations)

---

## Placeholders

Search the codebase for these placeholder markers — replace before going public:

- `{{COMPANY_URL_PLACEHOLDER}}` — in `Block/Adminhtml/About.php`
- `{{SUPPORT_EMAIL_PLACEHOLDER}}` — in `Block/Adminhtml/About.php` and `Support.php`
- `{{TAGLINE_PLACEHOLDER}}` and `{{ABOUT_PLACEHOLDER}}` — in `Block/Adminhtml/About.php`
- `{{DOCS_URL_PLACEHOLDER}}` — in `Block/Adminhtml/Support.php`
- Logo: `view/adminhtml/web/images/logo.svg` — replace with brand asset

---

## License

Open Software License (OSL 3.0) — see [LICENSE.md](LICENSE.md).
