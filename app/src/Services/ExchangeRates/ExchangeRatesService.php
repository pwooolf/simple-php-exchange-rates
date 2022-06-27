<?php

namespace App\Services\ExchangeRates;

use App\Services\ViewFormatter\FormatterInterface;

class ExchangeRatesService implements ExchangeRatesInterface
{
    private const RATES_URL = 'https://www.cbr-xml-daily.ru/daily_json.js';
    private const HEADERS = [
        'Content-Type' => 'Content-Type: application/json',
        'Accept' => 'Accept: application/json'
    ];

    private FormatterInterface $formatter;

    public function __construct(FormatterInterface $formatter)
    {
        $this->formatter = $formatter;
    }

    /**
     * @throws ExchangeRatesException
     */
    public function init(): void
    {
        $data = $this->getRates();

        $this->formattedData($data);
    }

    /**
     * @throws ExchangeRatesException
     */
    private function getRates(): array
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
        $this->formatter->formatter();
    }
}