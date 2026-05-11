<?php
/**
 * @copyright Copyright (c) 2026 Dlabsit
 * @license   OSL-3.0
 */

declare(strict_types=1);

namespace Dlabsit\Core\Api;

/**
 * Metadata for a single Dlabsit module, registered by that module via di.xml.
 */
interface ModuleInfoInterface
{
    public function getCode(): string;

    public function getName(): string;

    public function getDescription(): string;

    public function getVersion(): string;

    public function getComposerName(): string;

    /**
     * License tier:
     * - "free"                    → always free
     * - "free_os_paid_commerce"   → free on Magento Open Source, paid on Commerce
     * - "paid"                    → always paid
     */
    public function getLicenseTier(): string;

    public function getMinPhpVersion(): string;

    public function getMinMagentoVersion(): string;

    public function getRepositoryUrl(): string;

    public function getDocumentationUrl(): string;

    /**
     * Magento admin config section path, e.g. "xmlfeed" → links to
     * Stores → Configuration → XML Feeds. Used by Core Dashboard
     * to provide "Configure" links per module.
     */
    public function getConfigSection(): string;

    /**
     * Optional list of dependencies on other Dlabsit modules.
     *
     * @return string[] module codes
     */
    public function getDependsOn(): array;
}
