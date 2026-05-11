<?php
/**
 * @copyright Copyright (c) 2026 Dlabsit
 * @license   OSL-3.0
 */

declare(strict_types=1);

namespace Dlabsit\Core\Model;

use Dlabsit\Core\Api\ModuleInfoInterface;
use Dlabsit\Core\Api\ModuleRegistryInterface;

/**
 * Central registry of all Dlabsit modules installed in this Magento instance.
 * Modules register themselves by passing their info into di.xml like:
 *
 *   <type name="Dlabsit\Core\Model\ModuleRegistry">
 *     <arguments>
 *       <argument name="modules" xsi:type="array">
 *         <item name="xmlfeed" xsi:type="array">...</item>
 *       </argument>
 *     </arguments>
 *   </type>
 */
class ModuleRegistry implements ModuleRegistryInterface
{
    /** @var ModuleInfoInterface[] */
    private array $modules = [];

    public function __construct(
        private readonly ModuleInfoFactory $moduleInfoFactory,
        array $modules = []
    ) {
        foreach ($modules as $data) {
            if (is_array($data)) {
                $info = $this->moduleInfoFactory->create(['data' => $data]);
                $this->register($info);
            } elseif ($data instanceof ModuleInfoInterface) {
                $this->register($data);
            }
        }
    }

    public function register(ModuleInfoInterface $info): void
    {
        $this->modules[$info->getCode()] = $info;
    }

    public function getAll(): array
    {
        return $this->modules;
    }

    public function get(string $code): ?ModuleInfoInterface
    {
        return $this->modules[$code] ?? null;
    }

    public function has(string $code): bool
    {
        return isset($this->modules[$code]);
    }
}
