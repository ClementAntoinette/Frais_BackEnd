<?php

namespace NF\NotefraisBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NfNote
 *
 * @ORM\Table(name="nf_note")
 * @ORM\Entity
 */
class NfNote
{
    /**
     * @var integer
     *
     * @ORM\Column(name="note_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $noteId;

    /**
     * @var string
     *
     * @ORM\Column(name="note_comment", type="string", length=500, nullable=false)
     */
    private $noteComment;

    /**
     * @var string
     *
     * @ORM\Column(name="note_code_pin", type="string", length=6, nullable=false)
     */
    private $noteCodePin;

    /**
     * @var boolean
     *
     * @ORM\Column(name="note_verrou", type="boolean", nullable=false)
     */
    private $noteVerrou;

    /**
     * @var integer
     *
     * @ORM\Column(name="note_usr_id", type="integer", nullable=false)
     */
    private $noteUsrId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="note_date", type="date", nullable=false)
     */
    private $noteDate;



    /**
     * Get noteId
     *
     * @return integer
     */
    public function getNoteId()
    {
        return $this->noteId;
    }

    /**
     * Set noteComment
     *
     * @param string $noteComment
     *
     * @return NfNote
     */
    public function setNoteComment($noteComment)
    {
        $this->noteComment = $noteComment;

        return $this;
    }

    /**
     * Get noteComment
     *
     * @return string
     */
    public function getNoteComment()
    {
        return $this->noteComment;
    }

    /**
     * Set noteCodePin
     *
     * @param string $noteCodePin
     *
     * @return NfNote
     */
    public function setNoteCodePin($noteCodePin)
    {
        $this->noteCodePin = $noteCodePin;

        return $this;
    }

    /**
     * Get noteCodePin
     *
     * @return string
     */
    public function getNoteCodePin()
    {
        return $this->noteCodePin;
    }

    /**
     * Set noteVerrou
     *
     * @param boolean $noteVerrou
     *
     * @return NfNote
     */
    public function setNoteVerrou($noteVerrou)
    {
        $this->noteVerrou = $noteVerrou;

        return $this;
    }

    /**
     * Get noteVerrou
     *
     * @return boolean
     */
    public function getNoteVerrou()
    {
        return $this->noteVerrou;
    }

    /**
     * Set noteUsrId
     *
     * @param integer $noteUsrId
     *
     * @return NfNote
     */
    public function setNoteUsrId($noteUsrId)
    {
        $this->noteUsrId = $noteUsrId;

        return $this;
    }

    /**
     * Get noteUsrId
     *
     * @return integer
     */
    public function getNoteUsrId()
    {
        return $this->noteUsrId;
    }

    /**
     * Set noteDate
     *
     * @param \DateTime $noteDate
     *
     * @return NfNote
     */
    public function setNoteDate($noteDate)
    {
        $this->noteDate = $noteDate;

        return $this;
    }

    /**
     * Get noteDate
     *
     * @return \DateTime
     */
    public function getNoteDate()
    {
        return $this->noteDate;
    }
}
