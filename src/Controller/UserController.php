<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{
	private $serializer;
	private $passwordHasher;
	private $entityManager;

	public function __construct(
		SerializerInterface $serializer,
		UserPasswordHasherInterface $passwordHasher,
		EntityManagerInterface $entityManager
	) {
		$this->serializer = $serializer;
		$this->passwordHasher = $passwordHasher;
		$this->entityManager = $entityManager;
	}

	#[Route('/api/register', name: 'api_register', methods: 'POST')]
	public function register(Request $request): JsonResponse
	{

		$user = $this->serializer->deserialize($request->getContent(), User::class, 'json');

		if (count($user->getRoles()) > 1) {
			return new JsonResponse(["message" => "no permission"], 401);
		}

		$user = $this->createUser($user);

		$userJson = $this->serializer->serialize($user, 'json');
		return new JsonResponse($userJson, 201, [], true);
	}

	public function createUser(User $user)
	{
		// HashPassword
		$hashedPass = $this->passwordHasher->hashPassword($user, $user->getPassword());
		$user->setPassword($hashedPass);

		$this->entityManager->persist($user);
		$this->entityManager->flush();

		return $user;
	}
}
