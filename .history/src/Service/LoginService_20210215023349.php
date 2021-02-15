<?php
namespace App\Service;


use App\Entity\User;
use App\Entity\Connexion;
use App\Entity\Escale;
use App\Entity\Zone;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTExpiredEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTNotFoundEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTInvalidEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationFailureResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTAuthenticatedEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTDecodedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTEncodedEvent;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\EntityManagerInterface;

class LoginService
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /** @var EntityManagerInterface $emi */
    private $emi;

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack, EntityManagerInterface $emi)
    {
        $this->requestStack = $requestStack;
        $this->emi = $emi;
    }

    /**
     * @param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();

        $d = json_decode($this->requestStack ->getCurrentRequest()->getContent(), true);


        $escale = $d["params"]['escale'];

        /** @var ZOne $zone */
        $zone = $d["params"]['zone'];

        

        if (!$user instanceof UserInterface) {
            return;
        }
        if ($user instanceof User)

            //Enregistrer la connexion
            $connexion = new Connexion();
            $connexion->setUtilisateur($user);
            $connexion->setDateConnexion(new \Datetime());
            $this->emi->persist($connexion);
            $this->emi->flush();

            $data['data'] = array(
                'id'        => $user->getId(),
                'email'     => $user->getEmail(),
                'roles'     => $user->getRoles(),
                'reference' => $user->getReference(),
                'prenom' => $user->getPrenom(),
                'nom' => $user->getNom(),
                'sexe' => $user->getSexe(),
                'connexion' => $connexion->getId(),
                'zone' => $zone,
                'zoneChoice' => $zone,
                'escale' => $user->getEscale()->getId(),
                'escaleChoice' => $escale,
                'service' => $user->getService()->getId(),
                'serviceNom' => $user->getService()->getNom(),

            );

        $event->setData($data);
    }



        /**
         * @param AuthenticationSuccessEvent $event
         */

        public function onAuthenticationFailureResponse(AuthenticationFailureEvent $event)
        {
            return "<smile";
        }
}
