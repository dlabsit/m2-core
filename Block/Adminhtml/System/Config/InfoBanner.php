<?php
/**
 * @copyright Copyright (c) 2026 Dlabsit
 * @license   OSL-3.0
 */

declare(strict_types=1);

namespace Dlabsit\Core\Block\Adminhtml\System\Config;

use Dlabsit\Core\Api\ModuleRegistryInterface;
use Dlabsit\Core\Helper\Compatibility;
use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * Field-level renderer that draws a "Dlabsit module info" banner inside any
 * admin Configuration section. Subclass per module — each subclass returns
 * its own module code from getModuleCode().
 *
 * Subclassing is required because Magento's system.xml schema does NOT
 * pass arbitrary child elements through to $element->getData(); custom
 * XML elements outside the known schema (frontend_model, source_model,
 * comment, label, ...) are silently dropped.
 */
abstract class InfoBanner extends Field
{
    public function __construct(
        Context $context,
        private readonly ModuleRegistryInterface $registry,
        private readonly Compatibility $compatibility,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Return the Dlabsit module code this banner represents. Subclasses override.
     */
    abstract protected function getModuleCode(): string;

    /**
     * Draw the whole row (label + value cells merged) ourselves.
     */
    public function render(AbstractElement $element): string
    {
        $module = $this->registry->get($this->getModuleCode());

        if ($module === null) {
            return '<tr id="row_' . $element->getHtmlId() . '"><td colspan="3">'
                . '<em>Dlabsit module info unavailable (code: ' . $this->escapeHtml($this->getModuleCode()) . ').</em></td></tr>';
        }

        $phpStatus = $this->compatibility->checkPhpVersion($module->getMinPhpVersion());
        $magStatus = $this->compatibility->checkMagentoVersion($module->getMinMagentoVersion());
        $phpColor = $this->compatibility->getStatusColor($phpStatus);
        $magColor = $this->compatibility->getStatusColor($magStatus);
        $phpLabel = $this->compatibility->getStatusLabel($phpStatus);
        $magLabel = $this->compatibility->getStatusLabel($magStatus);

        $name = $this->escapeHtml($module->getName());
        $version = $this->escapeHtml($module->getVersion());
        $description = $this->escapeHtml($module->getDescription());
        $repoUrl = $this->escapeUrl($module->getRepositoryUrl());
        $docsUrl = $this->escapeUrl($module->getDocumentationUrl());

        $dashboardUrl = $this->escapeUrl($this->getUrl('dlabsit/dashboard/index'));
        $supportUrl = $this->escapeUrl($this->getUrl('dlabsit/support/index'));

        $currentPhp = $this->escapeHtml($this->compatibility->getCurrentPhpVersion());
        $minPhp = $this->escapeHtml($module->getMinPhpVersion());
        $currentMag = $this->escapeHtml($this->compatibility->getCurrentMagentoVersion());
        $minMag = $this->escapeHtml($module->getMinMagentoVersion());
        $edition = $this->escapeHtml($this->compatibility->getCurrentMagentoEdition());

        return <<<HTML
<tr id="row_{$element->getHtmlId()}">
    <td colspan="3" style="padding: 0 0 20px;">
        <div style="border: 2px solid #1e293b; border-radius: 6px; padding: 16px 20px; background: #f8fafc;">
            <div style="display: flex; align-items: flex-start; gap: 16px;">
                <svg width="56" height="56" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" style="flex-shrink: 0;">
                    <rect width="100" height="100" rx="20" fill="#1e293b"/>
                    <text x="50" y="68" font-family="system-ui,sans-serif"
                          font-size="44" font-weight="800" fill="#ffffff" text-anchor="middle">Dlabsit</text>
                </svg>
                <div style="flex-grow: 1;">
                    <div style="font-size: 16px; font-weight: 600; color: #1e293b;">
                        {$name} <span style="color: #64748b; font-weight: 400;">v{$version}</span>
                        <span style="background: #1e293b; color: #fff; padding: 2px 8px; border-radius: 3px; font-size: 11px; margin-left: 8px;">by Dlabsit</span>
                    </div>
                    <div style="color: #475569; font-size: 13px; margin-top: 6px;">{$description}</div>
                    <div style="margin-top: 10px; font-size: 12px; color: #475569;">
                        <strong>Compatibility:</strong>
                        <span style="color: {$phpColor}; font-weight: 600;">{$phpLabel}</span>
                        PHP {$currentPhp} / required ≥ {$minPhp}
                        &nbsp;·&nbsp;
                        <span style="color: {$magColor}; font-weight: 600;">{$magLabel}</span>
                        Magento {$edition} {$currentMag} / required ≥ {$minMag}
                    </div>
                    <div style="margin-top: 8px; font-size: 12px;">
                        <a href="{$dashboardUrl}">Dlabsit Dashboard</a>
                        &nbsp;·&nbsp;
                        <a href="{$supportUrl}">Support</a>
                        &nbsp;·&nbsp;
                        <a href="{$repoUrl}" target="_blank" rel="noopener">GitHub</a>
                        &nbsp;·&nbsp;
                        <a href="{$docsUrl}" target="_blank" rel="noopener">Docs</a>
                    </div>
                </div>
            </div>
        </div>
    </td>
</tr>
HTML;
    }
}
