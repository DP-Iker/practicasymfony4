<?php

namespace App\Entity;

use App\Repository\ProviderRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProviderRepository::class)
 */
class Provider
{
    // Simula un enum
    public const TYPE_HOTEL = 'hotel';
    public const TYPE_PISTA = 'pista';
    public const TYPE_COMPLEMENTO = 'complemento';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="El nombre es obligatorio")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="El email es obligatorio")
     * @Assert\Email(message="El email no es válido")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=9)
     * @Assert\NotBlank(message="El número de teléfono es obligatorio")
     * @Assert\Regex(
     *     pattern="/^[679]\d{8}$/",
     *     message="El número de teléfono debe ser español"
     * )
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank(message="El tipo es obligatorio")
     */
    private $type;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        // Verifica que sea de un tipo permitido
        if (!in_array($type, [self::TYPE_HOTEL, self::TYPE_PISTA, self::TYPE_COMPLEMENTO])) {
            throw new \InvalidArgumentException("Tipo no válido");
        }
        $this->type = $type;
        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
