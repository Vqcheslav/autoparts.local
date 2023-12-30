<?php

namespace App\DataFixtures;

use App\Entity\Autopart;
use App\Entity\AutopartOrder;
use App\Entity\Car;
use App\Entity\Manufacturer;
use App\Entity\User;
use App\Entity\Warehouse;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    const MIN_NUMBER = 0;
    const MAX_NUMBER = 4;

    public function __construct(
        public readonly UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = self::MIN_NUMBER; $i <= self::MAX_NUMBER; $i++) {
            $user = new User();
            $user->setName($this->getFakeValue('user', 'name'));
            $user->setEmail($this->getFakeValue('user', 'email', $i));
            $user->setPassword($this->passwordHasher->hashPassword(
                $user,
                $this->getFakeValue('user', 'password')
            ));
            $user->setRoles($this->getFakeValue('user', 'roles'));
            $manager->persist($user);

            $car = new Car();
            $car->setManufacturer($this->getFakeValue('car', 'manufacturer'));
            $car->setBrand($this->getFakeValue('car', 'brand'));
            $car->setModel($this->getFakeValue('car', 'model'));
            $car->setGeneration($this->getFakeValue('car', 'generation'));
            $car->setEngine($this->getFakeValue('car', 'engine'));
            $car->setYear($this->getFakeValue('car', 'year'));
            $manager->persist($car);

            $manufacturer = new Manufacturer();
            $manufacturer->setTitle($this->getFakeValue('manufacturer', 'title'));
            $manufacturer->setDescription($this->getFakeValue('manufacturer', 'description'));
            $manufacturer->setCountry($this->getFakeValue('manufacturer', 'country'));
            $manager->persist($manufacturer);

            $warehouse = new Warehouse();
            $warehouse->setAddress($this->getFakeValue('warehouse', 'address'));
            $warehouse->setOpeningHours($this->getFakeValue('warehouse', 'opening_hours'));
            $warehouse->setWorkingDays($this->getFakeValue('warehouse', 'working_days'));
            $warehouse->setPhoneNumber($this->getFakeValue('warehouse', 'phone_number'));
            $manager->persist($warehouse);

            $autopart = new Autopart();
            $autopart->setCar($car);
            $autopart->setWarehouse($warehouse);
            $autopart->setManufacturer($manufacturer);
            $autopart->setTitle($this->getFakeValue('autopart', 'title'));
            $autopart->setDescription($this->getFakeValue('autopart', 'description'));
            $autopart->setUpdatedAt(new \DateTimeImmutable($this->getFakeValue('autopart', 'updated_at')));
            $autopart->setCreatedAt(new \DateTimeImmutable($this->getFakeValue('autopart', 'created_at')));
            $manager->persist($autopart);

            $autopartOrder = new AutopartOrder();
            $autopartOrder->setUser($user);
            $autopartOrder->setAutopart($autopart);
            $autopartOrder->setAmount($this->getFakeValue('autopart_order', 'amount'));
            $autopartOrder->setStatus($this->getFakeValue('autopart_order', 'status'));
            $manager->persist($autopartOrder);
        }

        $manager->flush();
    }

    private function getFakeValue(string $tableName, string $field, ?int $numberOfArray = null): array|string
    {
        $fakeArray = [
            'user' => [
                [
                    'name' => 'Admin 1',
                    'email' => 'some_email1@email.com',
                    'password' => 'password',
                    'roles' => ['ROLE_ADMIN'],
                ],
                [
                    'name' => 'User 2',
                    'email' => 'some_email2@email.com',
                    'password' => 'password',
                    'roles' => ['ROLE_USER'],
                ],
                [
                    'name' => 'Admin 3',
                    'email' => 'some_email3@email.com',
                    'password' => 'password',
                    'roles' => ['ROLE_ADMIN'],
                ],
                [
                    'name' => 'User 4',
                    'email' => 'some_email4@email.com',
                    'password' => 'password',
                    'roles' => ['ROLE_USER'],
                ],
                [
                    'name' => 'Admin 5',
                    'email' => 'some_email5@email.com',
                    'password' => 'password',
                    'roles' => ['ROLE_MODERATOR'],
                ],
            ],
            'car' => [
                [
                    'manufacturer' => 'General Motors',
                    'brand' => 'Opel',
                    'model' => 'Astra',
                    'generation' => 'G',
                    'engine' => 'Y17DT',
                    'year' => '2001',
                ],
                [
                    'manufacturer' => 'General Motors',
                    'brand' => 'Opel',
                    'model' => 'Vectra',
                    'generation' => 'B',
                    'engine' => '1.6 diesel',
                    'year' => '2010',
                ],
                [
                    'manufacturer' => 'VAG',
                    'brand' => 'Volkswagen',
                    'model' => 'Passat',
                    'generation' => 'B7',
                    'engine' => '1.4 hybrid',
                    'year' => '2011',
                ],
                [
                    'manufacturer' => 'VAG',
                    'brand' => 'Volkswagen',
                    'model' => 'Passat',
                    'generation' => 'B6',
                    'engine' => '2.0 diesel',
                    'year' => '2010',
                ],
                [
                    'manufacturer' => 'VAG',
                    'brand' => 'Volkswagen',
                    'model' => 'Passat',
                    'generation' => 'B3',
                    'engine' => '1.9 diesel',
                    'year' => '1996',
                ],
            ],
            'manufacturer' => [
                [
                    'title' => 'Autoparts',
                    'description' => 'Autoparts - это китайский производитель',
                    'country' => 'CN',
                ],
                [
                    'title' => 'Patron',
                    'description' => 'Patron - это китайский производитель',
                    'country' => 'CN',
                ],
                [
                    'title' => 'Lemforder',
                    'description' => 'Lemforder - это немецкий производитель',
                    'country' => 'DE',
                ],
                [
                    'title' => 'Stellox',
                    'description' => 'Stellox - это китайский производитель',
                    'country' => 'CN',
                ],
                [
                    'title' => 'AutoRu',
                    'description' => 'AutoRu - это русский производитель',
                    'country' => 'RU',
                ],
            ],
            'warehouse' => [
                [
                    'address' => 'Россия, Москва, улица Задворская, д.100',
                    'opening_hours' => '10:00 - 18:00',
                    'working_days' => 'Без выходных',
                    'phone_number' => '+1020202030',
                ],
                [
                    'address' => 'Беларусь, Минск, улица Задворская, д.1',
                    'opening_hours' => '11:00 - 17:00',
                    'working_days' => 'Без выходных',
                    'phone_number' => '+12020202030',
                ],
                [
                    'address' => 'Украина, Киев, улица Задворская, д.10',
                    'opening_hours' => '09:00 - 17:00',
                    'working_days' => 'Без выходных',
                    'phone_number' => '+91020202030',
                ],
                [
                    'address' => 'Казахстан, Астана, улица Задворская, д.12',
                    'opening_hours' => '08:00 - 17:00',
                    'working_days' => 'Без выходных',
                    'phone_number' => '+891020202030',
                ],
                [
                    'address' => 'Польша, Варшава, улица Задворская, д.2',
                    'opening_hours' => '12:00 - 19:00',
                    'working_days' => 'Без выходных',
                    'phone_number' => '+3891020202030',
                ],
            ],
            'autopart' => [
                [
                    'title' => 'Тормозной диск',
                    'description' => 'Тормозной диск - часть тормозной системы',
                    'updated_at' => '2023/06/22 08:36',
                    'created_at' => '2023/06/22 08:35',
                ],
                [
                    'title' => 'Корзина сцепления',
                    'description' => 'Корзина сцепления - часть системы КПП',
                    'updated_at' => '2022/08/22 09:45',
                    'created_at' => '2022/08/22 07:35',
                ],
                [
                    'title' => 'Коробка передач',
                    'description' => 'Коробка передач - часть системы КПП',
                    'updated_at' => '2023/03/22 08:40',
                    'created_at' => '2023/03/22 08:35',
                ],
                [
                    'title' => 'Передняя дверь',
                    'description' => 'Передняя дверь - часть кузова',
                    'updated_at' => '2023/03/25 12:40',
                    'created_at' => '2023/03/24 01:24',
                ],
                [
                    'title' => 'Передняя фара',
                    'description' => 'Передняя фара - часть осветительной системы',
                    'updated_at' => '2023/05/02 17:34',
                    'created_at' => '2023/05/01 15:54',
                ],
            ],
            'autopart_order' => [
                [
                    'amount' => '5',
                    'status' => 'В ожидании',
                ],
                [
                    'amount' => '2',
                    'status' => 'В ожидании',
                ],
                [
                    'amount' => '1',
                    'status' => 'Доставляется',
                ],
                [
                    'amount' => '6',
                    'status' => 'Отменен',
                ],
                [
                    'amount' => '10',
                    'status' => 'Отменен',
                ],
            ]
        ];

        $randomInt = $this->getRandomInt(count($fakeArray[$tableName]) - 1);
        $numberOfArray = $numberOfArray ?? $randomInt;

        return $fakeArray[$tableName][$numberOfArray][$field];
    }

    private function getRandomInt(int $maxNumber = self::MAX_NUMBER): int
    {
        try {
            $randomInt = random_int(self::MIN_NUMBER, $maxNumber);
        } catch (\Throwable) {
            $randomInt = self::MIN_NUMBER;
        }

        return $randomInt;
    }
}
