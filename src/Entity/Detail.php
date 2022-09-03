<?php

namespace App\Entity;

use App\Repository\ProjectDetailRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: NoteDetailRepository::class)]
class Detail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\OneToOne(mappedBy: 'detail', cascade: ['persist', 'remove'])]
    private ?Note $note = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNote(): ?Note
    {
        return $this->note;
    }

    public function setNote(?Note $note): self
    {
        // unset the owning side of the relation if necessary
        if ($note === null && $this->note !== null) {
            $this->note->setDetail(null);
        }

        // set the owning side of the relation if necessary
        if ($note !== null && $note->getDetail() !== $this) {
            $note->setDetail($this);
        }

        $this->note = $note;

        return $this;
    }
}
