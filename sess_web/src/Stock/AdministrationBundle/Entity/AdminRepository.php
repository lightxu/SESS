<?php
namespace Stock\Administration\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
class AdminRepository extends EntityRepository implements AdminProviderInterface
{
    public function loadAdminByUsername($username)
    {
        $q = $this
            ->createQueryBuilder('u')
            ->where('u.username = :username')
            ->setParameter('username', $username)
            ->getQuery();
        try {
            // The Query::getSingleResult() method throws an exception
            // if there is no record matching the criteria.
            $Admin = $q->getSingleResult();
        } catch (NoResultException $e) {
            $message = sprintf(
                'Unable to find an active Admin identified by "%s".',
                $username
            );
            throw new AdminnameNotFoundException($message, 0, $e);
        }
        return $Admin;
    }

    public function refreshUser(UserInterface $admin)
    {
        $class = get_class($admin);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(
                sprintf(
                    'Instances of "%s" are not supported.',
                    $class
                )
            );
        }
        return $this->find($admin->getId());
    }

    public function supportsClass($class)
    {
        return $this->getEntityName() === $class
            || is_subclass_of($class, $this->getEntityName());
    }
}