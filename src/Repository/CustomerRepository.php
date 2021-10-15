<?php

namespace App\Repository;

use App\Contract\CustomerRepositoryInterface;
use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;


class CustomerRepository extends ServiceEntityRepository implements CustomerRepositoryInterface
{
    private $em;
    public function __construct(
        ManagerRegistry $registry,
        EntityManagerInterface $entityManager
    )
    {
        $this->em = $entityManager;
        parent::__construct($registry, Customer::class);
    }

    /**
     * @return Customer[]
     */
    public function getAllCustomers(): array
    {
        return $this->findAll();
    }

    /**
     * @param Customer $customer
     */
    public function createOrUpdateCustomer(Customer $customer): void
    {

        $this->save($customer);
    }

    public function removeCustomer(Customer $customer)
    {
        //dd($customer);
        $this->em->remove($customer);
        $this->em->flush();
    }


    /**
     * @param Customer $customer
     */
    private function save(Customer $customer)
    {
        $this->em->persist($customer);
        $this->em->flush();
    }
}
