<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MediasRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MediasRepository::class)
 * @ORM\EntityListeners({"App\EntityListener\MediaListener"})
 */
class Medias
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
    private $path;

    /**
     * @var UploadedFile|null
     * @Assert\Image
     */
    private ?UploadedFile $file = null;

    /**
     * @ORM\ManyToOne(targetEntity=Items::class, inversedBy="medias")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $item;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getItem(): ?Items
    {
        return $this->item;
    }

    public function setItem(?Items $item): self
    {
        $this->item = $item;

        return $this;
    }

    /**
     * Get the value of file
     *
     * @return  UploadedFile|null
     */ 
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set the value of file
     *
     * @param  UploadedFile|null  $file
     *
     * @return  self
     */ 
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }
}
