<?php

namespace NF\NotefraisBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NfFrais
 *
 * @ORM\Table(name="nf_frais")
 * @ORM\Entity
 */
class NfFrais
{
    /**
     * @var integer
     *
     * @ORM\Column(name="frais_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $fraisId;

    /**
     * @var string
     *
     * @ORM\Column(name="frais_comment", type="string", length=100, nullable=true)
     */
    private $fraisComment;

    /**
     * @var float
     *
     * @ORM\Column(name="frais_montant", type="float", precision=10, scale=0, nullable=false)
     */
    private $fraisMontant;

    /**
     * @var integer
     *
     * @ORM\Column(name="frais_id_note", type="integer", nullable=false)
     */
    private $fraisIdNote;

    /**
     * @var integer
     *
     * @ORM\Column(name="frais_id_type", type="integer", nullable=false)
     */
    private $fraisIdType;



    /**
     * Get fraisId
     *
     * @return integer
     */
    public function getFraisId()
    {
        return $this->fraisId;
    }

    /**
     * Set fraisComment
     *
     * @param string $fraisComment
     *
     * @return NfFrais
     */
    public function setFraisComment($fraisComment)
    {
        $this->fraisComment = $fraisComment;

        return $this;
    }

    /**
     * Get fraisComment
     *
     * @return string
     */
    public function getFraisComment()
    {
        return $this->fraisComment;
    }

    /**
     * Set fraisMontant
     *
     * @param float $fraisMontant
     *
     * @return NfFrais
     */
    public function setFraisMontant($fraisMontant)
    {
        $this->fraisMontant = $fraisMontant;

        return $this;
    }

    /**
     * Get fraisMontant
     *
     * @return float
     */
    public function getFraisMontant()
    {
        return $this->fraisMontant;
    }

    /**
     * Set fraisIdNote
     *
     * @param integer $fraisIdNote
     *
     * @return NfFrais
     */
    public function setFraisIdNote($fraisIdNote)
    {
        $this->fraisIdNote = $fraisIdNote;

        return $this;
    }

    /**
     * Get fraisIdNote
     *
     * @return integer
     */
    public function getFraisIdNote()
    {
        return $this->fraisIdNote;
    }

    /**
     * Set fraisIdType
     *
     * @param integer $fraisIdType
     *
     * @return NfFrais
     */
    public function setFraisIdType($fraisIdType)
    {
        $this->fraisIdType = $fraisIdType;

        return $this;
    }

    /**
     * Get fraisIdType
     *
     * @return integer
     */
    public function getFraisIdType()
    {
        return $this->fraisIdType;
    }
}
