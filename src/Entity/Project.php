<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $status = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column]
    private ?int $position = null;

    #[ORM\OneToMany(mappedBy: 'project', targetEntity: ProjectDetail::class, cascade: ["persist", "remove"])]
    private Collection $projectDetails;

    #[ORM\ManyToOne(inversedBy: 'project')]
    private ?ProjectGroup $projectGroup = null;

    public function __construct()
    {
        $this->projectDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return Collection<int, ProjectDetail>
     */
    public function getProjectDetails(): Collection
    {
        return $this->projectDetails;
    }

    public function addProjectDetail(ProjectDetail $projectDetail): self
    {
        if (!$this->projectDetails->contains($projectDetail)) {
            $this->projectDetails->add($projectDetail);
            $projectDetail->setProject($this);
        }

        return $this;
    }

    public function removeProjectDetail(ProjectDetail $projectDetail): self
    {
        if ($this->projectDetails->removeElement($projectDetail)) {
            // set the owning side to null (unless already changed)
            if ($projectDetail->getProject() === $this) {
                $projectDetail->setProject(null);
            }
        }

        return $this;
    }

    public function getProjectGroup(): ?ProjectGroup
    {
        return $this->projectGroup;
    }

    public function setProjectGroup(?ProjectGroup $projectGroup): self
    {
        $this->projectGroup = $projectGroup;

        return $this;
    }
}
