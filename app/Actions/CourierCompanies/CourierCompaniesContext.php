<?php

namespace App\Actions\CourierCompanies;

/**
 *
 */
class CourierCompaniesContext
{
    /**
     * @var CourierCompaniesInterface
     */
    private $courierCompany;

    /**
     * @param  CourierCompaniesInterface  $threePL
     */
    public function __construct(CourierCompaniesInterface $courierCompany)
    {
        $this->courierCompany = $courierCompany;
    }

    /**
     * @return mixed
     */
    public function createShipment(array $data)
    {
        return $this->courierCompany->createShipment($data);
    }

    /**
     * @return mixed
     */
    public function cancelShipment(array $data)
    {
        return $this->courierCompany->cancelShipment($data);
    }

    /**
     * @return mixed
     */
    public function trackShipment(array $data)
    {
        return $this->courierCompany->trackShipment($data);
    }

    /**
     * @return mixed
     */
    public function getLabel(array $data)
    {
        return $this->courierCompany->getLabel($data);
    }
}
