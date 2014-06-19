<?php
namespace Stock\AccountBundle\Entity;

class CompanyCheck
{

    protected $company_register_number;

    protected $name;

    protected $id_number;
    protected $license;

    /**
     * Set company_register_number
     *
     * @param string $companyRegisterNumber
     * @return CompanyCheck
     */
    public function setCompanyRegisterNumber($companyRegisterNumber)
    {
        $this->company_register_number = $companyRegisterNumber;

        return $this;
    }

    /**
     * Get company_register_number
     *
     * @return string 
     */
    public function getCompanyRegisterNumber()
    {
        return $this->company_register_number;
    }

    /**
     * Set license
     *
     * @param string $license
     * @return CompanyCheck
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
     * @return CompanyCheck
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
     * @return CompanyCheck
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
