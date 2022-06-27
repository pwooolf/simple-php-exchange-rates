<?php

namespace App\Services\ExchangeRates;

use App\Services\ViewFormatter\FormatterInterface;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

class ExchangeRatesService implements ExchangeRatesInterface
{
    private const RATES_URL = 'https://www.cbr-xml-daily.ru/daily_json.js';
    private const HEADERS = [
        'Content-Type' => 'Content-Type: application/json',
        'Accept' => 'Accept: application/json'
    ];
    private const CACHE_KEY = 'exchange_rates';

    private FormatterInterface $formatter;
    private CacheInterface $cache;

    public function __construct(FormatterInterface $formatter, CacheInterface $cache)
    {
        $this->formatter = $formatter;
        $this->cache = $cache;
    }

    /**
     * @throws ExchangeRatesException|InvalidArgumentException
     */
    public function init(): void
    {
        $data = $this->getRates();

        $this->formattedData($data);
    }

    /**
     * @throws ExchangeRatesException
     * @throws InvalidArgumentException
     */
    private function getRates(): array
    {
        $data = $this->cache->get(self::CACHE_KEY);
        if ($data !== null) {
            return $data;
        }

        $data = $this->sendRequest();
        $this->cache->set(self::CACHE_KEY, $data);
        return $data;
    }

    /**
     * @throws ExchangeRatesException
     */
    private function sendRequest(): array
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::RATES_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, self::HEADERS);

        $response = json_decode(curl_exec($ch), true);
        $httpCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);

        if ($httpCode !== 200) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new ExchangeRatesException(json_encode([$error, $httpCode]));

        }

        if (isset($response['errors'])) {
            curl_close($ch);
            throw new ExchangeRatesException(json_encode($response['errors']));
        }

        curl_close($ch);

        return $response;
    }

    private function formattedData(array $data): void
    {
        $this->formatter->getData($data);
        $this->formatter->show();
    }
}