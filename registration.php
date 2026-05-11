<?php
/**
 * Dlabsit Core Module for Magento 2
 *
 * Central hub for all Dlabsit modules: branding, compatibility matrix,
 * update notifications, license management placeholder.
 *
 * @category   Dlabsit
 * @package    Dlabsit_Core
 * @copyright  Copyright (c) 2026 Dlabsit (https://github.com/dlabsit)
 * @license    Open Software License ("OSL") v. 3.0 — https://opensource.org/licenses/OSL-3.0
 */

declare(strict_types=1);

use Magento\Framework\Component\ComponentRegistrar;

ComponentRegistrar::register(ComponentRegistrar::MODULE, 'Dlabsit_Core', __DIR__);
