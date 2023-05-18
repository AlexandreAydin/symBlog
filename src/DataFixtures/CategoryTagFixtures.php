<?php

namespace App\DataFixtures;

use App\Entity\Post\Category;
use App\Repository\Post\PostRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CategoryTagFixtures extends Fixture implements DependentFixtureInterface
{   
    public function __construct(
        private PostRepository $postRepository
    ) {
    }


    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $posts = $this->postRepository->findAll();
        

        // Category
        $categories = [];
        for ($i = 0; $i < 10; $i++) {
            $category = new Category();
            $name = $faker->unique()->sentence() . ' ' . $i; 
            $category->setName($name)
                ->setSlug((new Slugify())->slugify($name))
                ->setDescription(
                    mt_rand(0, 1) === 1 ? $faker->realText(254) : null
                );
        
            $manager->persist($category);
            $categories[] = $category;
        }


        foreach ($posts as $post) {
            for ($i = 0; $i < mt_rand(1, 5); $i++) {
                $post->addCategory(
                    $categories[mt_rand(0, count($categories) - 1)]
                );
            }
        }
        
        
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [PostFixtures::class];
    }

}


