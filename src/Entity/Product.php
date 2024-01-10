<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    private string $productName;
    private string $productDescription;
    private string $dossier;
    private int $categoryId;
    private int $inStock;
    private int $price;
    private string $brand;
    private int $nbrImage;
    private DateTimeInterface $dateAdded;

    public function __construct($productName, $productDescription, $dossier, $categoryId, $inStock, $price, $brand, $nbrImage, $dateAdded) {
        $this->productName = $productName;
        $this->productDescription = $productDescription;
        $this->dossier = $dossier;
        $this->categoryId = $categoryId;
        $this->inStock = $inStock;
        $this->price = $price;
        $this->brand = $brand;
        $this->nbrImage = $nbrImage;
        $this->dateAdded = new \DateTime($dateAdded);
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductName() {
        return $this->productName;
    }

    public function setProductName($productName) {
        $this->productName = $productName;
    }

    public function getProductDescription() {
        return $this->productDescription;
    }

    public function setProductDescription($productDescription) {
        $this->productDescription = $productDescription;
    }

    public function getDossier() {
        return $this->dossier;
    }

    public function setDossier($dossier) {
        $this->dossier = $dossier;
    }

    public function getCategoryId() {
        return $this->categoryId;
    }

    public function setCategoryId($categoryId) {
        $this->categoryId = $categoryId;
    }

    public function getInStock() {
        return $this->inStock;
    }

    public function setInStock($inStock) {
        $this->inStock = $inStock;
    }

    public function getPrice() {
        return $this->price;
    }

    public function setPrice($price) {
        $this->price = $price;
    }

    public function getBrand() {
        return $this->brand;
    }

    public function setBrand($brand) {
        $this->brand = $brand;
    }

    public function getNbrImage() {
        return $this->nbrImage;
    }

    public function setNbrImage($nbrImage) {
        $this->nbrImage = $nbrImage;
    }

    public function getDateAdded() {
        return $this->dateAdded;
    }

    public function setDateAdded($dateAdded) {
        $this->dateAdded = $dateAdded;
    }
}
