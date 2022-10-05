<?php

namespace App\Actions\CourierCompanies;

interface CourierCompaniesInterface
{
    public function createShipment(array $data);

    public function cancelShipment(array $data);

    public function trackShipment(array $data);

    public function getLabel(array $data);
}
