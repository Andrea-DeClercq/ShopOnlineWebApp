<?php

namespace App\Entity;

use App\Repository\CityRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CityRepository::class)]
class City
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    private string $name;
    private string $countryCode;
    private string $district;

    public function __construct($name, $countryCode, $district) {
        $this->name = $name;
        $this->countryCode = $countryCode;
        $this->district = $district;
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getCountryCode() {
        return $this->countryCode;
    }

    public function setCountryCode($countryCode) {
        $this->countryCode = $countryCode;
    }

    public function getDistrict() {
        return $this->district;
    }

    public function setDistrict($district) {
        $this->district = $district;
    }
}
