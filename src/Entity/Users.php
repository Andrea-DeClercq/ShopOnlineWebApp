<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
class Users
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    private string $userName;
    private string $userEmail;
    private string $userPhone;
    private string $userFname;
    private string $userLname;
    private string $userPassword;
    private int $userCityId;
    private string $userAdress;
    private int $userLoginStatus;
    public function __construct($userName, $userEmail, $userPhone, $userFname, $userLname, $userPassword, $userCityId, $userAdress, $userLoginStatus) {
        $this->userName = $userName;
        $this->userEmail = $userEmail;
        $this->userPhone = $userPhone;
        $this->userFname = $userFname;
        $this->userLname = $userLname;
        $this->userPassword = $userPassword;
        $this->userCityId = $userCityId;
        $this->userAdress = $userAdress;
        $this->userLoginStatus = $userLoginStatus;
    }
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getUserName() {
        return $this->userName;
    }

    public function getUserEmail() {
        return $this->userEmail;
    }

    public function getUserPhone() {
        return $this->userPhone;
    }

    public function getUserFname() {
        return $this->userFname;
    }

    public function getUserLname() {
        return $this->userLname;
    }

    public function getUserPassword() {
        return $this->userPassword;
    }

    public function getUserCityId() {
        return $this->userCityId;
    }

    public function getUserAdress() {
        return $this->userAdress;
    }

    public function getUserLoginStatus() {
        return $this->userLoginStatus;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setUserName($userName) {
        $this->userName = $userName;
    }

    public function setUserEmail($userEmail) {
        $this->userEmail = $userEmail;
    }

    public function setUserPhone($userPhone) {
        $this->userPhone = $userPhone;
    }

    public function setUserFname($userFname) {
        $this->userFname = $userFname;
    }

    public function setUserLname($userLname) {
        $this->userLname = $userLname;
    }

    public function setUserPassword($userPassword) {
        $this->userPassword = $userPassword;
    }

    public function setUserCityId($userCityId) {
        $this->userCityId = $userCityId;
    }

    public function setUserAdress($userAdress) {
        $this->userAdress = $userAdress;
    }

    public function setUserLoginStatus($userLoginStatus) {
        $this->userLoginStatus = $userLoginStatus;
    }
}
