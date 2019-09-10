<?php

namespace admin\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CodeBase
 *
 * @ORM\Table(name="code_base")
 * @ORM\Entity(repositoryClass="admin\UserBundle\Entity\CodeBaseRepository")
 */
class CodeBase {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $code
     *
     * @ORM\Column(name="code", type="string", length=50, unique=true)
     */
    private $code;

    /**
     * @var \DateTime $dateAjout
     *
     * @ORM\Column(name="date_ajout", type="datetime")
     */
    private $dateAjout;
    

    public function __construct($code) {
        $this->code = $code;
        $this->dateAjout = new \DateTime();
    }



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return CodeBase
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set dateAjout
     *
     * @param \DateTime $dateAjout
     * @return CodeBase
     */
    public function setDateAjout($dateAjout)
    {
        $this->dateAjout = $dateAjout;

        return $this;
    }

    /**
     * Get dateAjout
     *
     * @return \DateTime 
     */
    public function getDateAjout()
    {
        return $this->dateAjout;
    }
}
