<?php

namespace App\Actions\CourierCompanies;

use App\Actions\CourierCompanies\Aramex\AramexService;


class CourierCompaniesFactory
{
    /**
     * @throws \Exception
     */
    public static function getCourierCompany(string $name)
    {
        return match ($name) {
            'aramex' => new AramexService(),
            default => throw new \Exception("Unknown 3PL Company"),
        };
    }
}
