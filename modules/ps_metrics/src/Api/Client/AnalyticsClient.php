<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License version 3.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 */

namespace PrestaShop\Module\Ps_metrics\Api\Client;

use PrestaShop\AccountsAuth\Service\PsAccountsService;
use PrestaShop\Module\Ps_metrics\Handler\GuzzleApiResponseExceptionHandler;
use PrestaShop\Module\Ps_metrics\Middleware\CheckResponseMiddleware;
use PrestaShop\Module\Ps_metrics\Middleware\LogMiddleware;
use PrestaShop\Module\Ps_metrics\Middleware\ResponseMiddleware;
use PrestaShop\Module\Ps_metrics\Middleware\SentryMiddleware;

/**
 * 2007-2020 PrestaShop and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2020 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */
class AnalyticsClient extends ClientFactory
{
    /**
     * @var PsAccountsService
     */
    private $psAccountService;

    /**
     * AnalyticsClient constructor.
     *
     * @param CheckResponseMiddleware $checkResponseMiddleware
     * @param LogMiddleware $logMiddleware
     * @param SentryMiddleware $sentryMiddleware
     * @param ResponseMiddleware $responseMiddleWare
     * @param GuzzleApiResponseExceptionHandler $guzzleApiResponseExceptionHandler
     */
    public function __construct(
        CheckResponseMiddleware $checkResponseMiddleware,
        LogMiddleware $logMiddleware,
        SentryMiddleware $sentryMiddleware,
        ResponseMiddleWare $responseMiddleWare,
        GuzzleApiResponseExceptionHandler $guzzleApiResponseExceptionHandler
    ) {
        parent::__construct($checkResponseMiddleware, $logMiddleware, $sentryMiddleware, $responseMiddleWare, $guzzleApiResponseExceptionHandler);
        $this->psAccountService = new PsAccountsService();
    }

    /**
     * @return string[]
     */
    public function getHeader()
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->psAccountService->getOrRefreshToken(),
        ];
    }

    /**
     * @return string|false
     */
    public function getShopId()
    {
        return $this->psAccountService->getShopUuidV4();
    }
}
