<?php
namespace Stock\AccountBundle\Entity;

class LegalCheck
{

    protected $legal_register_number;

    protected $name;

    protected $id_number;
    protected $license;

    /**
     * Set legal_register_number
     *
     * @param string $legalRegisterNumber
     * @return LegalCheck
     */
    public function setLegalRegisterNumber($legalRegisterNumber)
    {
        $this->legal_register_number = $legalRegisterNumber;

        return $this;
    }

    /**
     * Get legal_register_number
     *
     * @return string 
     */
    public function getLegalRegisterNumber()
    {
        return $this->legal_register_number;
    }

    /**
     * Set license
     *
     * @param string $license
     * @return LegalCheck
     */
    public function setLicense($license)
    {
        $this->license = $license;

        return $this;
    }

    /**
     * Get license
     *
     * @return string 
     */
    public function getLicense()
    {
        return $this->license;
    }

    /**
     * Set id_number
     *
     * @param string $idNumber
     * @return LegalCheck
     */
    public function setIdNumber($idNumber)
    {
        $this->id_number = $idNumber;

        return $this;
    }

    /**
     * Get id_number
     *
     * @return string 
     */
    public function getIdNumber()
    {
        return $this->id_number;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return LegalCheck
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }
}
