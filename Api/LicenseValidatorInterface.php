<?php
/**
 * @copyright Copyright (c) 2026 Dlabsit
 * @license   OSL-3.0
 */

declare(strict_types=1);

namespace Dlabsit\Core\Api;

/**
 * License validation contract. In v1 this is a placeholder that always
 * returns valid — real implementation will call a remote license server.
 */
interface LicenseValidatorInterface
{
    public const STATUS_VALID = 'valid';
    public const STATUS_INVALID = 'invalid';
    public const STATUS_EXPIRED = 'expired';
    public const STATUS_GRACE_PERIOD = 'grace_period';
    public const STATUS_FREE_TIER = 'free_tier';
    public const STATUS_NOT_REQUIRED = 'not_required';

    /**
     * Is the module licensed to run?
     */
    public function isValid(string $moduleCode): bool;

    /**
     * Detailed status for display purposes.
     */
    public function getStatus(string $moduleCode): string;

    /**
     * Human-readable message for the admin.
     */
    public function getStatusMessage(string $moduleCode): string;
}
