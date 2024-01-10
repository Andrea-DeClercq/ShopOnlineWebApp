<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    private string $title;
    private string $webTitle;
    private int $parent;
    private int $level;

    public function __construct($title, $webTitle, $parent, $level) {
        $this->title = $title;
        $this->webTitle = $webTitle;
        $this->parent = $parent;
        $this->level = $level;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getWebTitle() {
        return $this->webTitle;
    }

    public function setWebTitle($webTitle) {
        $this->webTitle = $webTitle;
    }

    public function getParent() {
        return $this->parent;
    }

    public function setParent($parent) {
        $this->parent = $parent;
    }

    public function getLevel() {
        return $this->level;
    }

    public function setLevel($level) {
        $this->level = $level;
    }
}
