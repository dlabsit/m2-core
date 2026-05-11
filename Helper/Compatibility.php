<?php
/**
 * @copyright Copyright (c) 2026 Dlabsit
 * @license   OSL-3.0
 */

declare(strict_types=1);

namespace Dlabsit\Core\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\ProductMetadataInterface;

/**
 * Runtime compatibility checks: current PHP/Magento vs. module requirements,
 * plus Magento edition detection (Open Source vs. Commerce vs. Cloud).
 */
class Compatibility extends AbstractHelper
{
    public const EDITION_OPENSOURCE = 'Community';
    public const EDITION_COMMERCE = 'Enterprise';
    public const EDITION_B2B = 'B2B';
    public const EDITION_CLOUD = 'Cloud';

    public const STATUS_OK = 'ok';
    public const STATUS_WARN = 'warn';
    public const STATUS_FAIL = 'fail';

    public function __construct(
        Context $context,
        private readonly ProductMetadataInterface $productMetadata
    ) {
        parent::__construct($context);
    }

    public function getCurrentPhpVersion(): string
    {
        return PHP_VERSION;
    }

    public function getCurrentMagentoVersion(): string
    {
        return $this->productMetadata->getVersion();
    }

    public function getCurrentMagentoEdition(): string
    {
        $edition = $this->productMetadata->getEdition();
        if ($edition === 'Enterprise' && $this->isCloud()) {
            return self::EDITION_CLOUD;
        }
        return $edition;
    }

    public function isCloud(): bool
    {
        // Looks for infrastructure markers at the Magento root. Must happen
        // outside any DirectoryList root, so native path primitives are used.
        // phpcs:disable Magento2.Functions.DiscouragedFunction
        $root = defined('BP') ? BP : (dirname(__DIR__, 5));
        return file_exists($root . '/.magento.env.yaml')
            || file_exists($root . '/.magento.app.yaml');
        // phpcs:enable Magento2.Functions.DiscouragedFunction
    }

    public function isOpenSource(): bool
    {
        return $this->productMetadata->getEdition() === self::EDITION_OPENSOURCE;
    }

    public function isCommerce(): bool
    {
        return in_array(
            $this->productMetadata->getEdition(),
            [self::EDITION_COMMERCE, self::EDITION_B2B],
            true
        );
    }

    public function checkPhpVersion(string $minVersion): string
    {
        return version_compare(PHP_VERSION, $minVersion, '>=')
            ? self::STATUS_OK
            : self::STATUS_FAIL;
    }

    public function checkMagentoVersion(string $minVersion): string
    {
        return version_compare($this->getCurrentMagentoVersion(), $minVersion, '>=')
            ? self::STATUS_OK
            : self::STATUS_FAIL;
    }

    public function getStatusLabel(string $status): string
    {
        return match ($status) {
            self::STATUS_OK => '✓ OK',
            self::STATUS_WARN => '⚠ Warning',
            self::STATUS_FAIL => '✗ Fail',
            default => '?',
        };
    }

    public function getStatusColor(string $status): string
    {
        return match ($status) {
            self::STATUS_OK => '#1e7e34',
            self::STATUS_WARN => '#d68910',
            self::STATUS_FAIL => '#c0392b',
            default => '#888',
        };
    }
}
