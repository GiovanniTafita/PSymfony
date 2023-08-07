<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
  public function __construct(
    private UserRepository $userRepository,
    private EntityManagerInterface $entityManager,
    private UserPasswordHasherInterface $passwordHasher
  ) {
  }

  public function getUsers()
  {
    return $this->userRepository->findAll();
  }

  public function getUserBy($condition)
  {
    return $this->userRepository->findOneBy($condition);
  }

  public function saveUser(User $user)
  {
    $user = $this->hashPassword($user);

    $this->entityManager->persist($user);
    $this->entityManager->flush();

    return $user;
  }

  public function hashPassword(User $user)
  {
    $hashedPass = $this->passwordHasher->hashPassword($user, $user->getPassword());
    $user->setPassword($hashedPass);

    return $user;
  }
}
