<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user
            ->setUsername('nsivtsev')
            ->setName('Николай')
            ->setSurname('Сивцев')
            ->setPatronymic('Викторович')
            ->setTelephone('+79152120345')
            ->setEmail('fasper@yandex.ru')
            ->setPhoto('5ea92624da032.jpeg')
            ->setCv('5ea9263764a9e.pdf')
            ->setRoles(['ROLE_USER', 'ROLE_ADMIN']);

        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            '123123'// пароль
        ));
        $manager->persist($user);
        unset($user);

        $user = new User();
        $user
            ->setUsername('test')
            ->setName('John')
            ->setSurname('Doe')
            ->setPatronymic('')
            ->setTelephone('+79152222222')
            ->setEmail('')
            ->setRoles(['ROLE_USER']);

        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            '123456'// пароль
        ));
        $manager->persist($user);
        unset($user);

        $manager->flush();
    }
}