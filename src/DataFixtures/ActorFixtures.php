<?php
namespace App\DataFixtures;
use Faker;
use App\Entity\Actor;
use App\Services\SlugifyService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    const ACTORS = [
        'Andrew Lincoln',
        'Norman Reedus',
        'Lauren Cohan',
        'Danai Gurira',
        ];
    public function load(ObjectManager $manager)
    {
        foreach (self::ACTORS as $key => $actorName) {
            $actor = new Actor();
            $actor->setName($actorName);
            $actor->setSlug(SlugifyService::generate($actorName));
            $manager->persist($actor);
            $actor->addProgram($this->getReference('program_0'));
        }
        $faker = Faker\Factory::create();
        $nbActors = 250;
        for ($i = 0; $i < $nbActors; $i++) {
            $actor = new Actor();
            $actor->setName($faker->name);
            $actor->setSlug(SlugifyService::generate($actor->getName()));
            $manager->persist($actor);
            for($w = 0; $w < rand(1,4); $w++) {
                $actor->addProgram($this->getReference('program_' . rand(0, 55)));
            }
        }
        $manager->flush();
    }
    /**
     * @inheritDoc
     */
    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }
}