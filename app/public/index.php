<?php


use App\Services\ExchangeRates\ExchangeRatesService;
use App\Services\ViewFormatter\JsonFormatter;
use App\Services\ViewFormatter\XmlFormatter;

require __DIR__ . '/../vendor/autoload.php';

try {

    $formatter = new JsonFormatter();
//    $formatter = new XmlFormatter();

    $exchangeRatesService = new ExchangeRatesService($formatter);
    $exchangeRatesService->init();

} catch (Throwable $e) {
    echo $e->getMessage();
}
