<?php

namespace App\DataFixtures;

use App\Entity\MicroPost;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private const USERS = [
        [
            'username' => 'john_doe',
            'email' => 'john_doe@doe.com',
            'password' => 'john123',
            'fullName' => 'John Doe',
            'roles' => [User::ROLE_ADMIN, User::ROLE_USER]
        ],
        [
            'username' => 'rob_smith',
            'email' => 'rob_smith@smith.com',
            'password' => 'rob12345',
            'fullName' => 'Rob Smith',
            'roles' => [User::ROLE_USER]
        ],
        [
            'username' => 'marry_gold',
            'email' => 'marry_gold@gold.com',
            'password' => 'marry12345',
            'fullName' => 'Marry Gold',
            'roles' => [User::ROLE_USER]
        ],[
            'username' => 'super_admin',
            'email' => 'superadmin@gold.com',
            'password' => 'admin12345',
            'fullName' => 'Super Admin',
            'roles' => [User::ROLE_ADMIN]
        ],
    ];

    private const POST_TEXT = [
        'Hello, how are you?',
        'It\'s nice sunny weather today',
        'I need to buy some ice cream!',
        'I wanna buy a new car',
        'There\'s a problem with my phone',
        'I need to go to the doctor',
        'What are you up to today?',
        'Did you watch the game yesterday?',
        'How was your day?'
    ];

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadMicroPosts($manager);
    }

    public function loadUsers(ObjectManager $manager)
    {
        foreach (self::USERS as $postUser) {
            $user = new User();
            $user->setUsername($postUser['username']);
            $user->setFullName($postUser['fullName']);
            $user->setEmail($postUser['email']);
            $user->setPassword($this->encoder->encodePassword($user, $postUser['password']));
            $user->setRoles($postUser['roles']);
            $this->addReference($postUser['username'], $user);

            $manager->persist($user);
        }

        $manager->flush();
    }

    public function loadMicroPosts(ObjectManager $manager)
    {
        for ($i = 0; $i < 30; $i++) {
            $post = new MicroPost();
            $post->setText(self::POST_TEXT[array_rand(self::POST_TEXT) ]);

            $date = new \DateTime();
            $date->modify('-' . rand(0, 10) . ' day');

            $post->setTIme($date);
            $post->setUser($this->getReference(
                self::USERS[rand(0, count(self::USERS) - 1)]['username']
            ));

            $manager->persist($post);
        }

        $manager->flush();
    }
}
