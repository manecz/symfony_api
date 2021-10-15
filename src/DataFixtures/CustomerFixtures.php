<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CustomerFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        for($i=0; $i<=20; $i++)
        {
            $customer = new Customer('Name'.$i, $i.'email@siemens.com');
            $manager->persist($customer);
        }

        $manager->flush();
    }
}
