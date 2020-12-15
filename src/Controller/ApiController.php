<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Entity\User;
use App\Entity\Bagage;
use App\Entity\Escale;
use App\Entity\Vol;
use App\Entity\Service;
use App\Entity\Zone;
use App\Entity\Connexion;
use App\Entity\Passager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Core\Security;

class ApiController extends AbstractController
{

    public $emi;
    public $serialiser;

    // repository
    protected $repoUser;
    protected $repoVille;
    protected $repoBagage;
    protected $repoEscale;
    protected $repoVol;
    protected $repoZone;
    protected $repoConnexion;
    protected $repoService;
    protected $repoPassager;
    protected $security;


    // Constructeur
    public function __construct(Security $security, EntityManagerInterface $emi, SerializerInterface $serializer)
    {
        $this->emi = $emi;
        $this->serialiser = $serializer;
        $this -> security = $security;

      
        $this-> repoUser = $this -> emi -> getRepository(User::class);
        $this-> repoVille = $this -> emi -> getRepository(Ville::class);
        $this-> repoBagage = $this -> emi -> getRepository(Bagage::class);
        $this-> repoEscale = $this -> emi -> getRepository(Escale::class);
        $this-> repoVol = $this -> emi -> getRepository(Vol::class);
        $this-> repoZone = $this -> emi -> getRepository(Zone::class);
        $this-> repoConnexion = $this -> emi -> getRepository(Connexion::class);
        $this-> repoService = $this -> emi -> getRepository(Service::class);
        $this-> repoPassager = $this -> emi -> getRepository(Passager::class);

    }

    // retourne le message d'erreur et traitementOk false, en cas erreur
    protected function apiError($messageErreur)
    {
        return $this->json(["message" => $messageErreur, "traitementOk" => false], 500, ['Content-Type' => 'application/json']);
    }


    // Suppression
    protected function removeElement($object)
    {
        $id = $object->getId();
        try {
            $this->emi->remove($object);
        } catch (\Exception $e) {
            return $this->apiError("Erreur de suppression de données");
        }
        $this->emi->flush();
        return "success";
    }


    public function getUtilisateurConnecter(): User{
        /** @var User $user */
        $user = $this -> security->getUser();
        return $user;
    }






}
