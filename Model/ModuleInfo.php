<?php
/**
 * @copyright Copyright (c) 2026 Dlabsit
 * @license   OSL-3.0
 */

declare(strict_types=1);

namespace Dlabsit\Core\Model;

use Dlabsit\Core\Api\ModuleInfoInterface;
use Magento\Framework\DataObject;

class ModuleInfo extends DataObject implements ModuleInfoInterface
{
    public function getCode(): string
    {
        return (string) $this->getData('code');
    }

    public function getName(): string
    {
        return (string) $this->getData('name');
    }

    public function getDescription(): string
    {
        return (string) $this->getData('description');
    }

    public function getVersion(): string
    {
        return (string) $this->getData('version');
    }

    public function getComposerName(): string
    {
        return (string) $this->getData('composer_name');
    }

    public function getLicenseTier(): string
    {
        return (string) ($this->getData('license_tier') ?: 'free');
    }

    public function getMinPhpVersion(): string
    {
        return (string) ($this->getData('min_php_version') ?: '8.2');
    }

    public function getMinMagentoVersion(): string
    {
        return (string) ($this->getData('min_magento_version') ?: '2.4.7');
    }

    public function getRepositoryUrl(): string
    {
        return (string) $this->getData('repository_url');
    }

    public function getDocumentationUrl(): string
    {
        return (string) $this->getData('documentation_url');
    }

    public function getConfigSection(): string
    {
        return (string) $this->getData('config_section');
    }

    public function getDependsOn(): array
    {
        $deps = $this->getData('depends_on');
        return is_array($deps) ? $deps : [];
    }
}
