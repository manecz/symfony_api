<?php

namespace App\Validator;

use App\Entity\Customer;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class CustomerValidator implements CustomerValidatorInterface
{
    private $customerValidator;
    public function __construct(ValidatorInterface $validator)
    {
        $this->customerValidator = $validator;
    }

    public function validator(Customer $customer): array
    {
        $errors = $this->customerValidator->validate($customer);
        $errorsList = [];
        if (count($errors) > 0) {
            foreach ($errors as $error)
            {
                $errorsList [] = $error->getMessage();
            }
        }
        return $errorsList;
    }
}