<?php
// src/DataPersister/UserDataPersister.php

namespace App\DataPersister;

use App\Entity\Comment;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 *
 */
class CommentDataPersister implements ContextAwareDataPersisterInterface
{
    private $_entityManager;
    private $security;
    

    public function __construct(EntityManagerInterface $entityManager, Security $security) 
    {
        $this->_entityManager = $entityManager;
        $this->security = $security;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof Comment;
    }

    /**
     * @param Comment $data
     */
    public function persist($data, array $context = [])
    {
        $data->setUser($this->security->getUser());
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