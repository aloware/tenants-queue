<?php

namespace Aloware\TenantsQueue\Interfaces;

interface RepositoryInterface
{
    /**
     * @return array
     */
    public function addTenantNameToTheList($tenant);

    public function checkIfTenantNameAlreadyExist($tenant);

    public function removeTenantNameFromList($tenant);

    public function getTenantsName();

    public function getRandomTenantName();

    public function removeTenantsNamesList();

    public function getConnection();

}
