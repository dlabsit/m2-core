<?php
/**
 * @copyright Copyright (c) 2026 Dlabsit
 * @license   OSL-3.0
 */

declare(strict_types=1);

namespace Dlabsit\Core\Block\Adminhtml\Dashboard;

use Dlabsit\Core\Api\LicenseValidatorInterface;
use Dlabsit\Core\Api\ModuleInfoInterface;
use Dlabsit\Core\Api\ModuleRegistryInterface;
use Dlabsit\Core\Helper\Compatibility;
use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;

class ModuleList extends Template
{
    public function __construct(
        Context $context,
        private readonly ModuleRegistryInterface $registry,
        private readonly Compatibility $compatibility,
        private readonly LicenseValidatorInterface $license,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * @return ModuleInfoInterface[]
     */
    public function getModules(): array
    {
        return $this->registry->getAll();
    }

    public function getPhpStatus(ModuleInfoInterface $m): array
    {
        $status = $this->compatibility->checkPhpVersion($m->getMinPhpVersion());
        return [
            'label' => $this->compatibility->getStatusLabel($status),
            'color' => $this->compatibility->getStatusColor($status),
            'current' => $this->compatibility->getCurrentPhpVersion(),
            'required' => $m->getMinPhpVersion(),
        ];
    }

    public function getMagentoStatus(ModuleInfoInterface $m): array
    {
        $status = $this->compatibility->checkMagentoVersion($m->getMinMagentoVersion());
        return [
            'label' => $this->compatibility->getStatusLabel($status),
            'color' => $this->compatibility->getStatusColor($status),
            'current' => $this->compatibility->getCurrentMagentoVersion(),
            'required' => $m->getMinMagentoVersion(),
        ];
    }

    public function getLicenseStatus(ModuleInfoInterface $m): array
    {
        return [
            'status' => $this->license->getStatus($m->getCode()),
            'message' => $this->license->getStatusMessage($m->getCode()),
            'valid' => $this->license->isValid($m->getCode()),
        ];
    }

    public function getMagentoEdition(): string
    {
        return $this->compatibility->getCurrentMagentoEdition();
    }

    /**
     * Admin URL to Stores → Configuration → {section}
     */
    public function getConfigUrl(ModuleInfoInterface $m): string
    {
        $section = $m->getConfigSection();
        if ($section === '') {
            return '';
        }
        return $this->getUrl('adminhtml/system_config/edit', ['section' => $section]);
    }
}
