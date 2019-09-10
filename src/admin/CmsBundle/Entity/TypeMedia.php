<?php

namespace admin\CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypeMedia
 *
 * @ORM\Table(name="type_media")
 * @ORM\Entity(repositoryClass="admin\CmsBundle\Repository\TypeMediaRepository")
 */
class TypeMedia
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="libelleTypeMedia", type="string", length=40)
     */
    private $libelleTypeMedia;
    
    /**
     * @var string
     *
     * @ORM\Column(name="descriptionTypeMedia", type="string", length=140)
     */
    private $descriptionTypeMedia;
    
    /**
     * @var ArrayCollection medias
     * @ORM\OneToMany(targetEntity="admin\CmsBundle\Entity\Media", mappedBy="typeMedia")
     */
    private $medias; // Notez le « s », une annonce est liée à plusieurs candidatures


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set libelleTypeMedia
     *
     * @param string $libelleTypeMedia
     *
     * @return TypeMedia
     */
    public function setLibelleTypeMedia($libelleTypeMedia)
    {
        $this->libelleTypeMedia = $libelleTypeMedia;

        return $this;
    }

    /**
     * Get libelleTypeMedia
     *
     * @return string
     */
    public function getLibelleTypeMedia()
    {
        return $this->libelleTypeMedia;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->medias = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add media
     *
     * @param \admin\CmsBundle\Entity\Media $media
     *
     * @return TypeMedia
     */
    public function addMedia(\admin\CmsBundle\Entity\Media $media)
    {
        $this->medias[] = $media;

        return $this;
    }

    /**
     * Remove media
     *
     * @param \admin\CmsBundle\Entity\Media $media
     */
    public function removeMedia(\admin\CmsBundle\Entity\Media $media)
    {
        $this->medias->removeElement($media);
    }

    /**
     * Get medias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMedias()
    {
        return $this->medias;
    }

    /**
     * Set descriptionTypeMedia
     *
     * @param string $descriptionTypeMedia
     *
     * @return TypeMedia
     */
    public function setDescriptionTypeMedia($descriptionTypeMedia)
    {
        $this->descriptionTypeMedia = $descriptionTypeMedia;

        return $this;
    }

    /**
     * Get descriptionTypeMedia
     *
     * @return string
     */
    public function getDescriptionTypeMedia()
    {
        return $this->descriptionTypeMedia;
    }
}
