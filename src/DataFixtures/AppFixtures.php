<?php
namespace App\DataFixtures;

use App\Entity\MicroPost;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $post = new MicroPost();
            $post->setText('Some random text ' . rand(0, 500));
            $post->setTIme(new \DateTime());
            $manager->persist($post);
        }

        $manager->flush();
    }
}
