<?php

namespace App\Entity;

use App\Repository\AnnonceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=AnnonceRepository::class)
 */
class Annonce
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("annonce:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("annonce:read")
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("annonce:read")
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     * @Groups("annonce:read")
     */
    private $prix;

    /**
     * @ORM\Column(type="string", length=6)
     * @Groups("annonce:read")
     */
    private $code_postal;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("annonce:read")
     */
    private $ville;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->code_postal;
    }

    public function setCodePostal(string $code_postal): self
    {
        $this->code_postal = $code_postal;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }
}
