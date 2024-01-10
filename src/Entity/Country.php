<?php

namespace App\Entity;

use App\Repository\CountryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CountryRepository::class)]
class Country
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    private string $code;
    private string $name;
    private string $continent;
    private string $region;
    private string $localName;
    private int $capital;
    private string $code2;

    public function __construct($code, $name, $continent, $region, $localName, $capital, $code2) {
        $this->code = $code;
        $this->name = $name;
        $this->continent = $continent;
        $this->region = $region;
        $this->localName = $localName;
        $this->capital = $capital;
        $this->code2 = $code2;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode() {
        return $this->code;
    }

    public function setCode($code) {
        $this->code = $code;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getContinent() {
        return $this->continent;
    }

    public function setContinent($continent) {
        $this->continent = $continent;
    }

    public function getRegion() {
        return $this->region;
    }

    public function setRegion($region) {
        $this->region = $region;
    }

    public function getLocalName() {
        return $this->localName;
    }

    public function setLocalName($localName) {
        $this->localName = $localName;
    }

    public function getCapital() {
        return $this->capital;
    }

    public function setCapital($capital) {
        $this->capital = $capital;
    }

    public function getCode2() {
        return $this->code2;
    }

    public function setCode2($code2) {
        $this->code2 = $code2;
    }
}
