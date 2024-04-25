<?php

namespace App\Service;

use App\Entity\User;
use App\Model\UserDto;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class UserService extends AbstractService
{
    public function __construct(
        private UserRepository $userRepository,
        public UserPasswordHasherInterface $userPasswordHasher
    ) {
    }

    public function create(UserDto $userDTO): User
    {
        $user = new User();
        $user->setName($userDTO->name);
        $user->setEmail($userDTO->email);
        $hashedPassword = $this->userPasswordHasher->hashPassword($user, $userDTO->password);
        $user->setPassword($hashedPassword);

        $this->userRepository->save($user, true);

        return $user;
    }

    public function findAll()
    {
        return $this->userRepository->findAll();
    }
}