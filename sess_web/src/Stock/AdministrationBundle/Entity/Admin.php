<?php
namespace Stock\AdministrationBundle\Entity;

class Admin
{

    protected $admin_id;
    protected $admin_password;
    protected $admin_bank;

    /**
     * Set admin_id
     *
     * @param string $adminId
     * @return Admin
     */
    public function setAdminId($adminId)
    {
        $this->admin_id = $adminId;

        return $this;
    }

    /**
     * Get admin_id
     *
     * @return string 
     */
    public function getAdminId()
    {
        return $this->admin_id;
    }

    /**
     * Set admin_password
     *
     * @param string $adminPassword
     * @return Admin
     */
    public function setAdminPassword($adminPassword)
    {
        $this->admin_password = $adminPassword;

        return $this;
    }

    /**
     * Get admin_password
     *
     * @return string 
     */
    public function getAdminPassword()
    {
        return $this->admin_password;
    }

    /**
     * Set admin_bank
     *
     * @param string $adminBank
     * @return Admin
     */
    public function setAdminBank($adminBank)
    {
        $this->admin_bank = $adminBank;

        return $this;
    }

    /**
     * Get admin_bank
     *
     * @return string 
     */
    public function getAdminBank()
    {
        return $this->admin_bank;
    }
}
