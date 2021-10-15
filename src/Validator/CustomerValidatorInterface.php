<?php

namespace App\Validator;

use App\Entity\Customer;

interface CustomerValidatorInterface
{
    public function validator(Customer $customer);
}