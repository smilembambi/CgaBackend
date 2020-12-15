<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Escale;
use App\Entity\Service;
use App\Repository\UserRepository;
use App\Repository\EscaleRepository;
use Doctrine\ORM\EntityManagerInterface;
use PasswordService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use UserManager;
use Symfony\Component\Security\Core\Security;


class ApiUserController extends AbstractController
{

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var EntityManagerInterface
     */
    protected $emi;

    /**
     * UserManager constructor.
     * @param EntityManagerInterface $entityManager
     * @param UserRepository $userRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository
    ) {
        $this->emi = $entityManager;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/api/user", name="api_user_getAll", methods={"GET"})
     * @param UserRepository $userRepository
     * @return JsonResponse
     */
    public function getAll(UserRepository $userRepository)
    {
        return $this->json($this->userRepository->findAll(), 200, [], ['groups' => 'apiExport']);
    }


    /**
     * @Route("/api/user", name="api_user_create", methods={"POST"})
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @return array|string
     * @throws \Exception
     */
    public function create(Request $request, SerializerInterface $serializer, UserPasswordEncoderInterface $userPasswordEncoder)
    {

      
        $data = json_decode($request->getContent(), true);
        $userManager = new UserManager($this->emi, $this->userRepository);

        /**
         * @var User $user
         */

        $user  = new User();

        $user->setPassword("test"); 
        $user->setSexe($data["sexe"]); 

        if(isset($data["escale"])){
            /** @var Escale $escale */
            $escale = $this->emi->getRepository(Escale::class)->find($data["escale"]["id"]);
            $user->setEscale($escale);
        }

        if(isset($data["service"])){
            /** @var Service $service */
            $service = $this->emi-> getRepository(Service::class)->find($data["service"]["id"]);
            $user->setService($service);
        }
        
        $user->setPrenom($data["prenom"]); 
        $user->setNom($data["nom"]);

        $user->setUsername($data["email"]);
        $user->setEmail($data["email"]);
  
    
        //$user = $serializer->deserialize($data, User::class, 'json');
        $resultat =  $userManager->register($user,$userPasswordEncoder);
        return $this->json($resultat, 200, [], ['groups' => 'apiExport']);

    }



    
    /**
    * @Route("api/user/find", name="api_user_find", methods={"POST"})
    * @param Request $request
    * @return JsonResponse
    */
    public function find(Request $request, Security $security)
    {
        $data = json_decode($request->getContent(), true);

        /** @var User $user */
        $user = $security->getUser();
        


        $sel = "SELECT user 
                FROM " . User::class . " AS user 
                LEFT JOIN " . Escale::class . " as escale WITH escale.id = user.escale
                LEFT JOIN " . Service::class . " as service WITH service.id = user.service ";

        // Escale
        if($data['escale'] != 0){
            $sel .= " WHERE escale.id = " . $data['escale'];
        }

        if($data['service'] != 0  && $data['escale'] != 0 ){
            $sel .= " AND service.id = " . $data['service'];
        }else
        if($data['service'] != 0  && $data['escale'] == 0){
            $sel .= " WHERE service.id = ". $data['service'];
        }


        $query = $this->emi->createQuery($sel);
        $users = $query->getResult();

        return $this->json($users, 200, [], ['groups' => 'apiExport']);
    }



        /**
     * @Route("/api/user/passe", name="api_user_changePasse", methods={"POST"})
     * @param Request $request
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @return array|string
     * @throws \Exception
     */
    public function changePasse(Request $request, UserPasswordEncoderInterface $userPasswordEncoder)
    {

        $data = json_decode($request->getContent(), true);

        /** @var User $user */
        $user = $this->userRepository->findOneByReference($data["reference"]);
       
        $passwordService = new PasswordService();

        $nouveau = $passwordService->encode($user, $data['nouveau'],$userPasswordEncoder);

        if(!$passwordService->isValid($user,$data['ancien'],$userPasswordEncoder)){
            return $this->json("ancienPasseError", 200, [], ['groups' => 'apiExport']);
        }

        $user->setPassword($nouveau);

        $this->emi->persist($user);
        $this->emi->flush();


        return $this->json($user, 200, [], ['groups' => 'apiExport']);
    }



        /**
     * @Route("/api/user/{id}", name="api_user_update", methods={"PATCH"})
     * @param User $user
     * @param Request $request
     * @return array|string
     * @throws \Exception
     */
        public function update(User $user, Request $request)
        {

            if(!$user){
                return $this->json("Aucun utilisateur", 200, [], ['groups' => 'apiExport']);
            }

            $data = json_decode($request->getContent(), true);
            $userManager = new UserManager($this->emi, $this->userRepository);


            /** @var Escale $escale **/
            $escale = $this->emi->getRepository(Escale::class)->find($data["escale"]["id"]);

            /** @var Service $service **/
            $service = $this->emi->getRepository(Service::class)->find($data["service"]["id"]);


            $user->setNom($data['nom']);

            $user->setPrenom($data['prenom']);
            $user->setEmail($data['email']);
            $user->setSexe($data['sexe']);
            $user->setEscale($escale);
            $user->setService($service);
            $user->setRole("");

           $this->emi->persist($user);
           $this->emi->flush();


             return $this->json($user, 200, [], ['groups' => 'apiExport']);

        }


    /**
     * @Route("/api/user/{id}", name="api_user_delete",  methods={"DELETE"})
     * @param User $user
     * @return JsonResponse|RedirectResponse
     */
    public function delete(User $user)
    {
        if (!$user) {
            return $this->json("Aucun utilisateur", 200, [], ['groups' => 'apiExport']);
        }

        try {
            $this->emi->remove($user);
            $this->emi->flush();
        } catch (\Exception $e) {
            return $this->apiError("Erreur de suppression de données");
        }


        $resultat =  "success";
       return $this->json($resultat, 200, [], ['groups' => 'apiExport']);
    }


    /**
     * @Route("api/user/{id}", name="api_user_getOne", methods={"GET"})
     * @param User $user
     * @param UserRepository $userRepository
     * @return JsonResponse
     */
    public function getOne(User $user, UserRepository $userRepository)
    {
        if(!$user){
            return $this->json("Aucun utilisateur", 200, [], ['groups' => 'export']);
        }
        $user = $userRepository->find($user->getId());
        return $this->json($user, 200, [], ['groups' => 'apiExport']);
    }






}
