<?php


use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordService
{


    /**
     * @param object $entity
     * @param string $password
     * @return string
     */
    public function encode(object $entity, string $password, UserPasswordEncoderInterface $userPasswordEncoder): string
    {
        return $userPasswordEncoder->encodePassword($entity, $password);
    }

    /**
     * @param string $password
     * @return int
     */
    public function formatRequirement(string $password)
    {
        return preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W)#', $password);
    }

    /**
     * @param object $entity
     * @param string $password
     * @return bool
     */
    public function isValid(object $entity, string $password, UserPasswordEncoderInterface $userPasswordEncoder): bool
    {
        return $userPasswordEncoder->isPasswordValid($entity, $password);
    }
}
