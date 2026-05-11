<?php
/**
 * @copyright Copyright (c) 2026 Dlabsit
 * @license   OSL-3.0
 */

declare(strict_types=1);

namespace Dlabsit\Core\Api;

interface ModuleRegistryInterface
{
    /**
     * Register a module's metadata with the Core registry.
     */
    public function register(ModuleInfoInterface $info): void;

    /**
     * Get all registered modules (including Core itself).
     *
     * @return ModuleInfoInterface[] keyed by module code
     */
    public function getAll(): array;

    public function get(string $code): ?ModuleInfoInterface;

    public function has(string $code): bool;
}
