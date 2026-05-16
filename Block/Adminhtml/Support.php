<?php
/**
 * @copyright Copyright (c) 2026 Dlabsit
 * @license   OSL-3.0
 */

declare(strict_types=1);

namespace Dlabsit\Core\Block\Adminhtml;

use Dlabsit\Core\Api\ModuleRegistryInterface;
use Dlabsit\Core\Helper\Compatibility;
use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Serialize\Serializer\Json;

class Support extends Template
{
    public function __construct(
        Context $context,
        private readonly ModuleRegistryInterface $registry,
        private readonly Compatibility $compatibility,
        private readonly Json $json,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    public function getSupportEmail(): string
    {
        // {{SUPPORT_EMAIL_PLACEHOLDER}}
        return 'support@dlabsit.nl';
    }

    public function getDocumentationUrl(): string
    {
        // {{DOCS_URL_PLACEHOLDER}}
        return 'https://github.com/dlabsit';
    }

    public function getIssueTrackerUrl(): string
    {
        return 'https://github.com/dlabsit';
    }

    public function getSystemReport(): string
    {
        $modules = [];
        foreach ($this->registry->getAll() as $m) {
            $modules[$m->getCode()] = [
                'version' => $m->getVersion(),
                'composer_name' => $m->getComposerName(),
                'license_tier' => $m->getLicenseTier(),
            ];
        }

        return $this->json->serialize([
            'generated_at' => date('c'),
            'environment' => [
                'php_version' => $this->compatibility->getCurrentPhpVersion(),
                'magento_version' => $this->compatibility->getCurrentMagentoVersion(),
                'magento_edition' => $this->compatibility->getCurrentMagentoEdition(),
                'is_cloud' => $this->compatibility->isCloud(),
                'base_url' => $this->_storeManager->getStore()->getBaseUrl(),
            ],
            'modules' => $modules,
        ]);
    }
}
