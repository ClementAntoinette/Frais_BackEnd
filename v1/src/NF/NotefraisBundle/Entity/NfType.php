<?php

namespace NF\NotefraisBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NfType
 *
 * @ORM\Table(name="nf_type")
 * @ORM\Entity
 */
class NfType
{
    /**
     * @var integer
     *
     * @ORM\Column(name="type_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $typeId;

    /**
     * @var string
     *
     * @ORM\Column(name="type_nom", type="string", length=100, nullable=false)
     */
    private $typeNom;



    /**
     * Get typeId
     *
     * @return integer
     */
    public function getTypeId()
    {
        return $this->typeId;
    }

    /**
     * Set typeNom
     *
     * @param string $typeNom
     *
     * @return NfType
     */
    public function setTypeNom($typeNom)
    {
        $this->typeNom = $typeNom;

        return $this;
    }

    /**
     * Get typeNom
     *
     * @return string
     */
    public function getTypeNom()
    {
        return $this->typeNom;
    }
}
