<?php

namespace App\Service;

use App\Entity\User;
use App\Model\UserDTO;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        public UserPasswordHasherInterface $userPasswordHasher
    ) {
    }

    public function create(UserDTO $userDTO)
    {
        $user = new User();
        $user->setName($userDTO->name);
        $user->setEmail($userDTO->email);
        $user->setPassword(
            $this->userPasswordHasher->hashPassword($user, $userDTO->password)
        );

        $this->userRepository->save($user, true);

        return $user;
    }

    public function findAll()
    {
        return $this->userRepository->findAll();
    }
}