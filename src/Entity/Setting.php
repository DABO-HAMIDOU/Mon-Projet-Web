<?php

namespace App\Entity;

use Assert\Country;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\SettingRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SettingRepository::class)]
class Setting
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: "Le nom du site est obligatoire.")]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Le nom du site ne doit pas dépasser 255 caractères.',
    )]
    #[ORM\Column(length: 255)]
    private ?string $webSiteName = null;


    #[Assert\NotBlank(message: "L'url du site est obligatoire.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "L'url du site ne doit pas dépasser 255 caractères.",
    )]
    #[Assert\Url(message: "L'url n'est pas correcte.")]
    #[ORM\Column(length: 255)]
    private ?string $websiteUrl = null;


    #[Assert\NotBlank(message: "La description du site est obligatoire.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "La description du site ne doit pas dépasser 255 caractères.",
    )]
    #[ORM\Column(length: 255)]
    private ?string $description = null;


    #[Assert\NotBlank(message: "L'email du site est obligatoire.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "L'email du site ne doit pas dépasser 255 caractères.",
    )]
    #[Assert\Email(message: 'L\email {{ value }} est invalide.')]
    #[ORM\Column(length: 255)]
    private ?string $email = null;


    #[Assert\Regex(
        pattern: "/^[0-9\-\+\s\(\)]{6,30}$/",
        match: true,
        message: 'Le numéro de téléphone n\'est pas valide.'
    )]
    #[ORM\Column(length: 255)]
    private ?string $phone = null;


    #[Assert\Length(
        max: 255,
        maxMessage: "Le nom de la rue du site ne doit pas dépasser 255 caractères.",
    )]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $street = null;


    #[Assert\Length(
        max: 255,
        maxMessage: "Le nom de la ville du site ne doit pas dépasser 255 caractères.",
    )]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $city = null;


    #[Assert\Length(
        min: 4,
        max: 10,
        minMessage: "Le numéro du code postal du site doit avoir au moins 4 caractères",
        maxMessage: "Le numéro du code postal du site ne doit pas dépasser 255 caractères.",
    )]
    #[Assert\Regex(
        pattern: "/^[0-9]+$/",
        match: true,
        message: 'Le code postal n\'est pas valide.'
    )]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $postalCode = null;


    #[Assert\Length(
        max: 255,
        maxMessage: "Le nom du pays ne doit pas dépasser 255 caractères.",
    )]
    #[Assert\Country(message: "ce pays n'hesite pas !")]
    #[ORM\Column(length: 255)]
    private ?string $state = null;

    #[Assert\Length(
        max: 255,
        maxMessage: "L'url  Facebook  ne doit pas dépasser 255 caractères.",
    )]
    #[Assert\Url(message: "L'url n'est pas correcte.")]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $facebookLink = null;

    #[Assert\Length(
        max: 255,
        maxMessage: "L'url instagram  ne doit pas dépasser 255 caractères.",
    )]
    #[Assert\Url(message: "L'url n'est pas correcte.")]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $instagramLink = null;

    #[Assert\Length(
        max: 255,
        maxMessage: "L'url TikTok  ne doit pas dépasser 255 caractères.",
    )]
    #[Assert\Url(message: "L'url n'est pas correcte.")]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tiktokLink = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWebSiteName(): ?string
    {
        return $this->webSiteName;
    }

    public function setWebSiteName(?string $webSiteName): static
    {
        $this->webSiteName = $webSiteName;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(?string $street): static
    {
        $this->street = $street;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postal_code): static
    {
        $this->postalCode = $postal_code;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getFacebookLink(): ?string
    {
        return $this->facebookLink;
    }

    public function setFacebookLink(?string $facebookLink): static
    {
        $this->facebookLink = $facebookLink;

        return $this;
    }

    public function getInstagramLink(): ?string
    {
        return $this->instagramLink;
    }

    public function setInstagramLink(?string $instagramLink): static
    {
        $this->instagramLink = $instagramLink;

        return $this;
    }

    public function getTiktokLink(): ?string
    {
        return $this->tiktokLink;
    }

    public function setTiktokLink(?string $tiktokLink): static
    {
        $this->tiktokLink = $tiktokLink;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeImmutable
    {
        return $this->createAt;
    }

    public function setCreateAt(?\DateTimeImmutable $createAt): static
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getWebsiteUrl(): ?string
    {
        return $this->websiteUrl;
    }

    public function setWebsiteUrl(?string $websiteUrl): static
    {
        $this->websiteUrl = $websiteUrl;

        return $this;
    }
}
