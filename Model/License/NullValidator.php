<?php
/**
 * @copyright Copyright (c) 2026 Dlabsit
 * @license   OSL-3.0
 */

declare(strict_types=1);

namespace Dlabsit\Core\Model\License;

use Dlabsit\Core\Api\LicenseValidatorInterface;
use Dlabsit\Core\Api\ModuleRegistryInterface;
use Dlabsit\Core\Helper\Compatibility;

/**
 * Placeholder license validator for v1.
 *
 * Rules (v1):
 * - "free" tier           → always valid
 * - "free_os_paid_commerce" → valid on Open Source, needs-license on Commerce (but returns valid here)
 * - "paid" tier           → returns valid (real check deferred to v2)
 *
 * v2 will replace this with a remote-server validator.
 */
class NullValidator implements LicenseValidatorInterface
{
    public function __construct(
        private readonly ModuleRegistryInterface $registry,
        private readonly Compatibility $compatibility
    ) {
    }

    public function isValid(string $moduleCode): bool
    {
        // v1: always valid. Real enforcement comes in v2.
        return true;
    }

    public function getStatus(string $moduleCode): string
    {
        $module = $this->registry->get($moduleCode);
        if ($module === null) {
            return self::STATUS_NOT_REQUIRED;
        }

        $tier = $module->getLicenseTier();

        if ($tier === 'free') {
            return self::STATUS_FREE_TIER;
        }

        if ($tier === 'free_os_paid_commerce') {
            return $this->compatibility->isOpenSource()
                ? self::STATUS_FREE_TIER
                : self::STATUS_VALID; // v1 placeholder — v2 will remote-validate
        }

        // paid tier
        return self::STATUS_VALID;
    }

    public function getStatusMessage(string $moduleCode): string
    {
        $status = $this->getStatus($moduleCode);
        return match ($status) {
            self::STATUS_FREE_TIER => __('Free tier (no license required).')->render(),
            self::STATUS_VALID => __('License active (development placeholder — remote validation pending).')->render(),
            self::STATUS_NOT_REQUIRED => __('No license info available.')->render(),
            default => __('Unknown.')->render(),
        };
    }
}
