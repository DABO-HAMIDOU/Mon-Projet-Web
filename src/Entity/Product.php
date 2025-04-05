<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Gedmo\Mapping\Annotation as Gedmo;
// DON'T forget the following use statement!!!
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

// #[Vich\Uploadable]
#[UniqueEntity('code', message:"ce code est deja celui d'un autre produit")]
#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[Vich\Uploadable]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

//relation product et user
    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?User $user = null;

    //relation product et category
   

    // #[Assert\NotBlank(message: "veillez choisir au moins une categorie.")]
    // #[Assert\Type(
    //     type: Category::class,
    //     message: "Cette categorie n'est pas valide.",
    // )]
    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'products')]
    private Collection $categories;

    #[Assert\NotBlank(message: "Le code est obligatoire!")]
    #[Assert\Length(
        max: 255,
        maxMessage: 'le code du produit ne doit pas depasser {{ limit }} caractéres',
    )]
    #[ORM\Column(length: 255, unique : true)]
    private ?string $code = null;

    #[Assert\NotBlank(message: "Le nom du produit est obligatoire.")]
    #[Assert\Length(
        max: 255,
        maxMessage: 'le nom du produit ne doit pas depasser {{ limit }} caractéres',
    )]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Gedmo\Slug(fields: ['name'])]
    #[ORM\Column(length: 255)]
    private ?string $slug = null;


    #[Assert\NotBlank(message: "La courte description est obligatoire.")]
    #[Assert\Length(
        max: 1000,
        maxMessage: 'la courte description ne doit pas depasser {{ limit }} caractéres',
    )]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $shortDescription = null;

    #[Assert\Length(
        max: 10000,
        maxMessage: 'la longue description ne doit pas depasser {{ limit }} caractéres',
    )]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $longDescription = null;

    #[Assert\Length(
        max: 255,
        maxMessage: 'la marque ne doit pas depasser {{ limit }} caractéres',
    )]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $brand = null;


    #[Assert\NotBlank(message: "Le prix de vente est obligatoire.")]
    #[Assert\Positive(message: "Le prix de vente doit être un nombre positif.")]
    #[Assert\Regex(
        pattern: "/^\d+(\.\d{1,2})?$/",
        message: "Le prix de vente doit être un entier ou un nombre décimal avec au plus deux décimales."
    )]
    #[Assert\Range(
        min: 0.1,
        max: 9999999,
        notInRangeMessage: 'Le prix de vente doit être compris entre {{ min }} euros {{ max }} euros.',
    )]
    #[ORM\Column]
    private ?float $sellingPrice = null;

    #[Assert\NotBlank(message: "La quantité est obligatoire.")]
    #[Assert\Type(type: "integer", message: "La quantité doit être un entier.")]
    #[Assert\GreaterThanOrEqual(value: 0, message: "La quantité doit être un nombre positif.")]
    #[ORM\Column]
    private ?int $quantity = null;

    #[Assert\Type(type: "bool", message: "Le champ nouvel'arrivage doit être de type booléen.")]
    #[ORM\Column]
    private ?bool $isNewArrival = false;

    #[Assert\Type(type: "bool", message: "Le champ meilleur vente doit être de type booléen.")]
    #[ORM\Column]
    private ?bool $isBetterSeller = false;

    #[Assert\NotBlank(message: "La disponibilité est obligatoire!")]
    #[Assert\Type(type: "bool", message: "La disponibilitédoit être de type booléen.")]
    #[ORM\Column]
    private ?bool $isAvaible = false;

    #[Assert\File(
        maxSize: '2048k',
        extensions: ['jpg','jpeg','png','webp'],
        maxSizeMessage:"La taille de l'image ne doit pas dépasser 2MO",
        extensionsMessage: "seul les formats jpg, jpeg, png, webp sont acceptés",
    )]
    // NOTE: This is not a mapped field of entity metadata, just a simple property.
    #[Vich\UploadableField(mapping: 'products', fileNameProperty: 'image')]
    private ?File $imageFile = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, OrderItem>
     */
    #[ORM\OneToMany(targetEntity: OrderItem::class, mappedBy: 'product')]
    private Collection $orderItems;

  

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->orderItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(string $shortDescription): static
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    public function getLongDescription(): ?string
    {
        return $this->longDescription;
    }

    public function setLongDescription(string $longDescription): static
    {
        $this->longDescription = $longDescription;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(?string $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function getSellingPrice(): ?float
    {
        return $this->sellingPrice;
    }

    public function setSellingPrice(float $sellingPrice): static
    {
        $this->sellingPrice = $sellingPrice;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function isNewArrival(): ?bool
    {
        return $this->isNewArrival;
    }

    public function setIsNewArrival(bool $isNewArrival): static
    {
        $this->isNewArrival = $isNewArrival;

        return $this;
    }

    public function isBetterSeller(): ?bool
    {
        return $this->isBetterSeller;
    }

    public function setIsBetterSeller(bool $isBetterSeller): static
    {
        $this->isBetterSeller = $isBetterSeller;

        return $this;
    }

    public function isAvaible(): ?bool
    {
        return $this->isAvaible;
    }

    public function setIsAvaible(bool $isAvaible): static
    {
        $this->isAvaible = $isAvaible;

        return $this;
    }
    
    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

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

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        $this->categories->removeElement($category);

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, OrderItem>
     */
    public function getOrderItems(): Collection
    {
        return $this->orderItems;
    }

    public function addOrderItem(OrderItem $orderItem): static
    {
        if (!$this->orderItems->contains($orderItem)) {
            $this->orderItems->add($orderItem);
            $orderItem->setProduct($this);
        }

        return $this;
    }

    public function removeOrderItem(OrderItem $orderItem): static
    {
        if ($this->orderItems->removeElement($orderItem)) {
            // set the owning side to null (unless already changed)
            if ($orderItem->getProduct() === $this) {
                $orderItem->setProduct(null);
            }
        }

        return $this;
    }
}
