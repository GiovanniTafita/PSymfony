<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{
	public function __construct(
		private SerializerInterface $serializer,
		private UserService $userService
	) {
	}

	#[IsGranted('ROLE_ADMIN')]
	#[Route('/api/users', name: 'api_users', methods: 'GET')]
	public function getUsers(): JsonResponse
	{
		$users = $this->userService->getUsers();
		$usersJson = $this->serializer->serialize($users, 'json');
		return new JsonResponse($usersJson, 201, [], true);
	}

	#[IsGranted('ROLE_MANAGER')]
	#[Route('/api/register', name: 'api_register', methods: 'POST')]
	public function register(Request $request): JsonResponse
	{

		$user = $this->serializer->deserialize($request->getContent(), User::class, 'json');

		if (count($user->getRoles()) > 1) {
			return new JsonResponse(["message" => "no permission"], 401);
		}

		$user = $this->userService->saveUser($user);

		$userJson = $this->serializer->serialize($user, 'json');
		return new JsonResponse($userJson, 201, [], true);
	}

	#[IsGranted('ROLE_ADMIN')]
	#[Route('/api/update_roles/{id}', name: 'api_roles', methods: 'PUT')]
	public function updateRoles(User $currentUser, Request $request): JsonResponse
	{
		$updatedUser = $this->serializer->deserialize(
			$request->getContent(),
			User::class,
			'json',
			[AbstractNormalizer::OBJECT_TO_POPULATE => $currentUser]
		);

		$this->userService->saveUser($updatedUser);

		$userJson = $this->serializer->serialize($updatedUser, 'json');
		return new JsonResponse($userJson, 200, [], true);
	}
}
