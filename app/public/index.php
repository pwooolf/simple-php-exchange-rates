<?php


use App\Services\ExchangeRates\ExchangeRatesService;

require __DIR__ . '/../vendor/autoload.php';

try {

    $exchangeRatesService = new ExchangeRatesService();
    $exchangeRatesService->init();



} catch (Throwable $e) {
    echo $e->getMessage();
}
