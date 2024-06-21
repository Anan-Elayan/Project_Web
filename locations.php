<?php
class Locations
{
    private $id ;
    private $pickupName;
    private $city;
    private $telephone;
    private $street;
    private $country;
    private $propertyNumber;
    private $postalCode;

    /**
     * @param $id
     * @param $pickupName
     * @param $city
     * @param $telephone
     * @param $street
     * @param $country
     * @param $propertyNumber
     * @param $postalCode
     */
    public function __construct( $pickupName, $city, $telephone, $street, $country, $propertyNumber, $postalCode)
    {

        $this->pickupName = $pickupName;
        $this->city = $city;
        $this->telephone = $telephone;
        $this->street = $street;
        $this->country = $country;
        $this->propertyNumber = $propertyNumber;
        $this->postalCode = $postalCode;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getPickupName()
    {
        return $this->pickupName;
    }

    /**
     * @param mixed $pickupName
     */
    public function setPickupName($pickupName): void
    {
        $this->pickupName = $pickupName;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city): void
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * @param mixed $telephone
     */
    public function setTelephone($telephone): void
    {
        $this->telephone = $telephone;
    }

    /**
     * @return mixed
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param mixed $street
     */
    public function setStreet($street): void
    {
        $this->street = $street;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country): void
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getPropertyNumber()
    {
        return $this->propertyNumber;
    }

    /**
     * @param mixed $propertyNumber
     */
    public function setPropertyNumber($propertyNumber): void
    {
        $this->propertyNumber = $propertyNumber;
    }

    /**
     * @return mixed
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * @param mixed $postalCode
     */
    public function setPostalCode($postalCode): void
    {
        $this->postalCode = $postalCode;
    }


}
