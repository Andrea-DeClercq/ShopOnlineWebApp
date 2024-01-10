<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartRepository::class)]
class Cart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    private int $idUser;
    private int $idProduct;
    private int $quantity;
    private bool $payed;
    private bool $confirmed;

    public function __construct($idUser, $idProduct, $quantity, $payed, $confirmed) {
        $this->idUser = $idUser;
        $this->idProduct = $idProduct;
        $this->quantity = $quantity;
        $this->payed = $payed;
        $this->confirmed = $confirmed;
    }
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getIdUser() {
        return $this->idUser;
    }

    public function setIdUser($idUser) {
        $this->idUser = $idUser;
    }

    public function getIdProduct() {
        return $this->idProduct;
    }

    public function setIdProduct($idProduct) {
        $this->idProduct = $idProduct;
    }

    public function getQuantity() {
        return $this->quantity;
    }

    public function setQuantity($quantity) {
        $this->quantity = $quantity;
    }

    public function isPayed() {
        return $this->payed;
    }

    public function setPayed($payed) {
        $this->payed = $payed;
    }

    public function isConfirmed() {
        return $this->confirmed;
    }

    public function setConfirmed($confirmed) {
        $this->confirmed = $confirmed;
    }
}
