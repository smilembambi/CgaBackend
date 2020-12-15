<?php

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var PasswordService
     */
    protected $passwordService;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * UserManager constructor.
     * @param EntityManagerInterface $entityManager
     * @param PasswordService $passwordService
     * @param UserRepository $userRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository
    ) {
        $this->em = $entityManager;
        $this->userRepository = $userRepository;
    }

    /**
     * @param string $email
     * @return mixed
     */
    public function findByEmail(string $email)
    {
        $user = $this->userRepository->findByEmail($email);

        if ($user) {
            return $user[0];
        }
        return null;
    }

    /**
     * @param string $email
     */
    public function findByUsername(string $username)
    {
        $user = $this->userRepository->findByUsername($username);

        if ($user) {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @return array|string
     * @throws \Exception
     */
    public function register(User $user, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $nouveauUsername = strtolower($this->initiales($user->getprenom()).$user->getNom());
        $username = $this->compositionUsername($nouveauUsername);

        // $user->setUsername($username);

        if ($this->findByEmail($user->getEmail())) {
            throw new BadRequestHttpException('Cette adresse email a déjà été utilisé.');
        }

        $passwordService = new PasswordService();
       // $password = $this->passgen1(10);
        $password = "cga2020";
        $pass = $passwordService->encode($user, $password ,$userPasswordEncoder);
        $user->setPassword($pass);
        $user->setReference($this->referenceFormat());

        $user->setCreateAt(new \DateTime());
        $user->setUpdateAt(new \DateTime());

        $this->em->persist($user);
        $this->em->flush();


        return [
            'message' => 'Création de compte enregistrée.',
            'user' => $user
        ];
    }

    function compositionUsername(string $username){
        $i = 0;

        if($this->findByUsername($username)){
            $i++;
            $username = $this->compositionUsername($username.$i);
        }
       return $username;

    }

    /**
     * REP + ANNEE + MOIS + JOUR + TOKEN GENERER.
     *
     * @return string
     */
    public function referenceFormat()
    {
        return 'REP'.substr(date('Y'), 2).date('md').uniqid();
    }

    public function passgen1($nbChar) {
        $chaine ="mnoTUzS5678kVvwxy9WXYZRNCDEFrslq41GtuaHIJKpOPQA23LcdefghiBMbj0";
        srand((double)microtime()*1000000);
        $pass = '';
        for($i=0; $i<$nbChar; $i++){
            $pass .= $chaine[rand()%strlen($chaine)];
            }
        return $pass;
    }

   public function initiales($nom){
            $nom_initiale = ''; // déclare le recipient
            $n_mot = explode(" ",$nom);
            foreach($n_mot as $lettre){
                $nom_initiale .= $lettre{0}.'.';
            }
            return strtoupper($nom_initiale);
    }




}
