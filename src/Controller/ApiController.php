<?php

namespace App\Controller;
use App\Contract\CustomerRepositoryInterface;
use App\Entity\Customer;
use App\Validator\CustomerValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ApiController extends AbstractController
{
    private $customerRepository;
    private $customerValidator;
    public function __construct(
        CustomerRepositoryInterface $repository,
        CustomerValidator $validator
    )
    {
        $this->customerValidator = $validator;
        $this->customerRepository = $repository;
    }

    /**
     * @Route("/", name="customer_all", methods={"get"})
     */
    public function index(): Response
    {
        $customers = $this->customerRepository->getAllCustomers();
        if(!$customers)
        {
            return new JsonResponse(['data'=>null], Response::HTTP_OK);
        }
        $data = [];
        foreach ($customers as $customer)
        {
            $data [] = $customer->toArray($customer);
        }
        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/", name="customer_create", methods={"post"})
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $newCustomer = new Customer($data['name']??null, $data['email']??null);
        $error = $this->customerValidator->validator($newCustomer);

        if($error)
        {
            return new JsonResponse($error, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $this->customerRepository-> createOrUpdateCustomer($newCustomer);
        return new JsonResponse(['status'=>'Customer created', 'data'=>$newCustomer->toArray()], Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @param Customer $customer
     * @return JsonResponse
     * @Route("/{id}", name="customer_update", methods={"put"})
     */
    public function update(Request $request, Customer $customer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $customer
            ->setName(empty($data['name'])?$customer->getName(): $data['name'])
            ->setEmail(empty($data['email'])?$customer->getEmail(): $data['email']);
        $error = $this->customerValidator->validator($customer);
        if($error)
        {
            return new JsonResponse($error, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $this->customerRepository->createOrUpdateCustomer($customer);
        return new JsonResponse(['status'=>'Customer updated', 'data'=>$customer->toArray()], Response::HTTP_OK);
    }

    /**
     * @param Customer $customer
     * @return JsonResponse
     * @Route("/{id}", name="customer_show", methods={"get"})
     */
    public function show(Customer $customer): JsonResponse
    {
        return new JsonResponse($customer->toArray(), Response::HTTP_OK);
    }

    /**
     * @param Customer $customer
     * @return JsonResponse
     * @Route("/{id}", name="customer_delete", methods={"delete"})
     */
    public function delete(Customer $customer): JsonResponse
    {
        $this->customerRepository->removeCustomer($customer);
        return new JsonResponse(['status'=>'Customer removed'], Response::HTTP_NO_CONTENT);
    }
}