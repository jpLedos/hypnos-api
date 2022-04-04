<?php

namespace App\Doctrine;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Security;

class FilterUserQueryExtension implements QueryCollectionExtensionInterface
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }


    public function applyToCollection(  QueryBuilder $queryBuilder, 
                                        QueryNameGeneratorInterface $queryNameGenerator, 
                                        string $resourceClass, 
                                        string $operationName = null)
    {
        $rootAlias  = $queryBuilder->getRootAliases()[0];
        $user = $this->security->getUser();

        if ($resourceClass === User::class && !$this->security->isGranted('ROLE_ADMIN') ) {
                $queryBuilder
                ->andWhere(sprintf('%s.id = :current_user', $rootAlias ))
                ->setParameter('current_user', $user->getId());
        }
    }


}