<?php
namespace App\DataFixtures;

use Faker;
use Faker\Factory;
use App\Entity\Season;
use App\Entity\Episode;
use App\Entity\Program;
use Faker\Provider\Lorem;
use App\Services\SlugifyService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();

        $i=0;
        for($j=0; $j<56; $j++) {
            for($k=0; $k < rand(1,10); $k++){
                $i++;
                $season = new Season();
                $season ->setNumber($k+1)
                        ->setYear(2009+$k)
                        ->setDescription($faker->paragraph($nbSentences = rand(1, 5), $variableNbSentences = true))
                        ->setProgram($this->getReference('program_' . $j));                        
                $manager->persist($season);
                $this->addReference('saison_' . $i, $season);
                

                    for($l=0; $l < rand(1,25); $l++){
                        $episode = new Episode();
                        $episode    ->setNumber($l+1)
                                    ->setTitle($faker->sentence($nbWords = rand(1,5), $variableNbWords = true))
                                    ->setSynopsis($faker->paragraph($nbSentences = rand(1, 5), $variableNbSentences = true))
                                    ->setSeason($this->getReference('saison_' . $i))
                                    ->setSlug(SlugifyService::generate($episode->getTitle()));
                        $manager->persist($episode);
                    }
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


