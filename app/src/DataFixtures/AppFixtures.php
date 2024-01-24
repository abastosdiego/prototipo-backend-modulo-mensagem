<?php

namespace App\DataFixtures;

use App\Entity\Unidade;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $unidade = new Unidade();
        $unidade->setSigla('DAdM');
        $unidade->setNome('Diretoria de Administração da Marinha');
        $manager->persist($unidade);
        
        $unidade = new Unidade();
        $unidade->setSigla('DFM');
        $unidade->setNome('Diretoria de Finanças da Marinha');
        $manager->persist($unidade);

        $unidade = new Unidade();
        $unidade->setSigla('SGM');
        $unidade->setNome('Secretaria Geral da Marinha');
        $manager->persist($unidade);

        $manager->flush();
    }
}
