<?php
/**
 * @copyright Copyright (c) 2026 Dlabsit
 * @license   OSL-3.0
 */

declare(strict_types=1);

namespace Dlabsit\Core\Block\Adminhtml;

use Dlabsit\Core\Api\ModuleRegistryInterface;
use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;

class About extends Template
{
    public function __construct(
        Context $context,
        private readonly ModuleRegistryInterface $registry,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    public function getCompanyName(): string
    {
        return 'Dlabsit';
    }

    public function getCompanyTagline(): string
    {
        // {{TAGLINE_PLACEHOLDER}}
        return 'Magento 2 extensions for modern merchants.';
    }

    public function getCompanyDescription(): string
    {
        // {{ABOUT_PLACEHOLDER}}
        return 'Dlabsit builds high-quality Magento 2 extensions focused on '
            . 'marketplace integrations, feed generation, and commerce tooling. '
            . 'All our modules follow Magento 2 coding standards, are covered by '
            . 'PSR-12, use strict types, and work on PHP 8.2+.';
    }

    public function getCompanyUrl(): string
    {
        // {{COMPANY_URL_PLACEHOLDER}}
        return 'https://dlabsit.example';
    }

    public function getSupportEmail(): string
    {
        // {{SUPPORT_EMAIL_PLACEHOLDER}}
        return 'support@dlabsit.example';
    }

    public function getGithubOrganization(): string
    {
        return 'https://github.com/dlabsit';
    }

    public function getModules(): array
    {
        return $this->registry->getAll();
    }
}
