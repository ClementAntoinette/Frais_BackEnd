<?php

namespace NF\NotefraisBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NfUtilisateurUsr
 *
 * @ORM\Table(name="nf_utilisateur_usr")
 * @ORM\Entity
 */
class NfUtilisateurUsr
{
    /**
     * @var integer
     *
     * @ORM\Column(name="usr_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $usrId;

    /**
     * @var string
     *
     * @ORM\Column(name="usr_nom", type="string", length=50, nullable=false)
     */
    private $usrNom;

    /**
     * @var string
     *
     * @ORM\Column(name="usr_prenom", type="string", length=50, nullable=false)
     */
    private $usrPrenom;

    /**
     * @var string
     *
     * @ORM\Column(name="usr_genre", type="string", length=10, nullable=false)
     */
    private $usrGenre;

    /**
     * @var string
     *
     * @ORM\Column(name="usr_mail", type="string", length=70, nullable=false)
     */
    private $usrMail;



    /**
     * Get usrId
     *
     * @return integer
     */
    public function getUsrId()
    {
        return $this->usrId;
    }

    /**
     * Set usrNom
     *
     * @param string $usrNom
     *
     * @return NfUtilisateurUsr
     */
    public function setUsrNom($usrNom)
    {
        $this->usrNom = $usrNom;

        return $this;
    }

    /**
     * Get usrNom
     *
     * @return string
     */
    public function getUsrNom()
    {
        return $this->usrNom;
    }

    /**
     * Set usrPrenom
     *
     * @param string $usrPrenom
     *
     * @return NfUtilisateurUsr
     */
    public function setUsrPrenom($usrPrenom)
    {
        $this->usrPrenom = $usrPrenom;

        return $this;
    }

    /**
     * Get usrPrenom
     *
     * @return string
     */
    public function getUsrPrenom()
    {
        return $this->usrPrenom;
    }

    /**
     * Set usrGenre
     *
     * @param string $usrGenre
     *
     * @return NfUtilisateurUsr
     */
    public function setUsrGenre($usrGenre)
    {
        $this->usrGenre = $usrGenre;

        return $this;
    }

    /**
     * Get usrGenre
     *
     * @return string
     */
    public function getUsrGenre()
    {
        return $this->usrGenre;
    }

    /**
     * Set usrMail
     *
     * @param string $usrMail
     *
     * @return NfUtilisateurUsr
     */
    public function setUsrMail($usrMail)
    {
        $this->usrMail = $usrMail;

        return $this;
    }

    /**
     * Get usrMail
     *
     * @return string
     */
    public function getUsrMail()
    {
        return $this->usrMail;
    }
}
