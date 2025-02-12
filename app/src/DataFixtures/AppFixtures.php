<?php

namespace App\DataFixtures;

use App\Entity\Unidade;
use App\Entity\Usuario;
use App\Repository\UnidadeRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AppFixtures extends Fixture
{
    public function __construct(private HttpClientInterface $httpClient, private UnidadeRepository $unidadeRepository, private UserPasswordHasherInterface $passwordHasher){}

    public function load(ObjectManager $manager): void
    {
        //### Unidades ###//

        $unidadeDAdM = new Unidade();
        $unidadeDAdM->setSigla('DAdM');
        $unidadeDAdM->setNome('Diretoria de Administração da Marinha');
        $manager->persist($unidadeDAdM);

        $unidade = new Unidade();
        $unidade->setSigla('DFM');
        $unidade->setNome('Diretoria de Finanças da Marinha');
        $manager->persist($unidade);

        $unidade = new Unidade();
        $unidade->setSigla('SGM');
        $unidade->setNome('Secretaria-Geral da Marinha');

        $manager->persist($unidade);
        $manager->flush();


        //### Usuários ###//
        $senha = '123456';

        $usuario = new Usuario(nip:'17090148', nome:'Diego Bastos', email:'d.bastos@marinha.mil.br', unidade:$unidadeDAdM);
        $hashedPassword = $this->passwordHasher->hashPassword($usuario, $senha);
        $usuario->setPassword($hashedPassword);
        $manager->persist($usuario);

        $usuario = new Usuario(nip:'81234567', nome:'João da Silva', email:'joao@marinha.mil.br', unidade:$unidadeDAdM);
        $hashedPassword = $this->passwordHasher->hashPassword($usuario, $senha);
        $usuario->setPassword($hashedPassword);
        $manager->persist($usuario);

        $manager->flush();
    }
}
