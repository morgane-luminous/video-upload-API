<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Controller\CreateVideoAction;
use App\Repository\VideoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation\Uploadable;
use Vich\UploaderBundle\Mapping\Annotation\UploadableField;
use Symfony\Component\Validator\Constraints as Assert;

#[Entity(repositoryClass: VideoRepository::class)]
#[Uploadable]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(
            controller: CreateVideoAction::class,
            denormalizationContext: ['groups' => ['video:create']],
            security: "is_granted('ROLE_ADMIN')",
            deserialize: false,
        ),
        new Delete(security: "is_granted('ROLE_ADMIN') and object.getAddedBy() == user"),
        new Patch(
            denormalizationContext: ['groups' => ['video:update']],
            security: "is_granted('ROLE_ADMIN') and object.getAddedBy() == user"
        )
    ],
    normalizationContext: ['groups' => ['video:read']],
)]
class Video
{
    private const AUTHORIZED_MIME_TYPES = [
        'mp4',
        'mpeg',
        'ogg',
        'webm',
        'avi',
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['video:read', 'video:create', 'video:update'])]
    private string $title;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['video:read', 'video:create', 'video:update'])]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Expression(
        "null === this.getFile() or this.getUri() === null",
        message: 'You can\'t set $uri if a file is already provided.',
    )]
    #[Assert\Url]
    #[Assert\NotBlank(allowNull: true)]
    #[Groups(['video:read', 'video:create', 'video:update'])]
    private ?string $uri = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fileName = null;

    #[UploadableField(
        mapping: 'videos',
        fileNameProperty: 'fileName',
        size: 'fileSize',
        mimeType: 'mimeType'
    )]
    #[Assert\File(
//        maxSize: '150M',
        extensions: self::AUTHORIZED_MIME_TYPES
    )]
    #[Assert\Expression(
        "null === this.getFile() and null === this.getUri()",
        message: 'You have to send a file or a URI.',
        negate: false,
    )]
    #[Groups(['video:create'])]
    private ?File $file = null;

    #[ORM\Column(type: 'integer',nullable: true)]
    private ?int $fileSize = null;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $createdAt;

    #[ORM\ManyToMany(targetEntity: Category::class, mappedBy: 'videos')]
    #[Groups(['video:create', 'video:update'])]
    private Collection $categories;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mimeType = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'user_id', nullable: false)]
    private ?User $addedBy = null;

    public function __construct(
    ) {
        $this->createdAt = new \DateTimeImmutable();
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(?string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * @return File|null
     */
    public function getFile(): ?File
    {
        return $this->file;
    }

    /**
     * @param File|null $file
     */
    public function setFile(?File $file): self
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getFileSize(): ?int
    {
        return $this->fileSize;
    }

    /**
     * @param int|null $fileSize
     */
    public function setFileSize(?int $fileSize): self
    {
        $this->fileSize = $fileSize;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->addVideo($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->removeElement($category)) {
            $category->removeVideo($this);
        }

        return $this;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(?string $mimeType): self
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    public function getAddedBy(): ?User
    {
        return $this->addedBy;
    }

    public function setAddedBy(?User $addedBy): self
    {
        $this->addedBy = $addedBy;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUri(): ?string
    {
        return $this->uri;
    }

    /**
     * @param string|null $uri
     */
    public function setUri(?string $uri): Video
    {
        $this->uri = $uri;

        return $this;
    }
}
