<?php

namespace App\Entity;

use App\Repository\CategoriasRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoriasRepository::class)
 */
class Categorias
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(type="integer")
     */
    private $numImagenes;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getNumImagenes(): ?int
    {
        return $this->numImagenes;
    }

    public function setNumImagenes(int $numImagenes): self
    {
        $this->numImagenes = $numImagenes;

        return $this;
    }



    public function __toString(){
        return $this->getNombre();
    }
}
