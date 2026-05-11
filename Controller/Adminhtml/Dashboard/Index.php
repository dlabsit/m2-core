<?php
/**
 * @copyright Copyright (c) 2026 Dlabsit
 * @license   OSL-3.0
 */

declare(strict_types=1);

namespace Dlabsit\Core\Controller\Adminhtml\Dashboard;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;

class Index extends Action
{
    public const ADMIN_RESOURCE = 'Dlabsit_Core::dashboard';

    public function __construct(Context $context)
    {
        parent::__construct($context);
    }

    public function execute(): \Magento\Framework\View\Result\Page
    {
        /** @var \Magento\Framework\View\Result\Page $result */
        $result = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $result->setActiveMenu('Dlabsit_Core::dashboard');
        $result->getConfig()->getTitle()->prepend(__('Dlabsit Dashboard'));
        return $result;
    }
}
