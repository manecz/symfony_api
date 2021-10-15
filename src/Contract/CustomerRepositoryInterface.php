<?php

namespace App\Contract;

use App\Entity\Customer;

interface CustomerRepositoryInterface
{
    public function getAllCustomers();
    public function createOrUpdateCustomer(Customer $customer);
    public function removeCustomer(Customer $customer);
}