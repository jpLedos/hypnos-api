<?php
// src/DataPersister/UserDataPersister.php

namespace App\DataPersister;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Security;

/**
 *
 */
class UserDataPersister implements ContextAwareDataPersisterInterface
{
    private $_entityManager;
    private $_passwordEncoder;
    private $security;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordEncoder,
        Security $security
    ) {
        $this->_entityManager = $entityManager;
        $this->_passwordEncoder = $passwordEncoder;
        $this->security = $security;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof User;
    }

    /**
     * @param User $data
     */
    public function persist($data, array $context = [])
    {
        // password management
        if ($data->getPlainPassword()) {
            $data->setPassword(
                $this->_passwordEncoder->hashPassword(
                    $data,
                    $data->getPlainPassword()
                )
            );

            $data->eraseCredentials();
        }

        //add ROLE_MANAGER if user created by ADMIN
        if ($this->security->isGranted('ROLE_ADMIN')){
            $data->setRoles(["ROLE_MANAGER"]);
        }

            //allergens management
            // $allergenRepository = $this->_entityManager->getRepository(Allergen::class);

            // foreach ($data->getAllergens() as $allergen) {
            //     $a = $allergenRepository->findOneByTitle($allergen->getTitle());
    
            //     // if the allergen exists, don't persist it
            //     if ($a !== null) {
            //         $data->removeAllergen($allergen);
            //         $data->addAllergen($a);
            //     } else {
            //         $this->_entityManager->persist($allergen);
            //     }
            // }

        $this->_entityManager->persist($data);
        $this->_entityManager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function remove($data, array $context = [])
    {
        $this->_entityManager->remove($data);
        $this->_entityManager->flush();
    }
}