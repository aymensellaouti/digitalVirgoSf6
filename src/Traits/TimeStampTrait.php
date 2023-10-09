<?php

namespace App\Traits;

use Doctrine\ORM\Mapping as ORM;

trait TimeStampTrait
{
    #[ORM\Column(type:'datetime', nullable: true)]
    private ?\DateTime $createdAt;

    #[ORM\Column(type:'datetime', nullable: true)]
    private ?\DateTime $updatedAt;

    /**
     * @return \DateTime|null
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime|null $createdAt
     */
    public function setCreatedAt(?\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime|null
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime|null $updatedAt
     */
    public function setUpdatedAt(?\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    #[ORM\PrePersist()]
    public function onPersist() {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }
    #[ORM\PreUpdate()]
    public function onUpdate() {
        $this->updatedAt = new \DateTime();
    }


}