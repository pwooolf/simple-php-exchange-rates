<?php


use App\Services\ExchangeRates\ExchangeRatesService;
use App\Services\ViewFormatter\JsonFormatter;
use App\Services\ViewFormatter\XmlFormatter;
use Cache\Adapter\Apcu\ApcuCachePool;

require __DIR__ . '/../vendor/autoload.php';

try {

    $cache = new ApcuCachePool();
//    $formatter = new JsonFormatter();
    $formatter = new XmlFormatter();

    $exchangeRatesService = new ExchangeRatesService($formatter, $cache);
    $exchangeRatesService->init();

} catch (Throwable $e) {
    echo $e->getMessage();
}
