<?php

namespace App\Controller;
use App\Controller\User;
use App\Entity\Connexion;
use App\Entity\Zone;
use App\Entity\EscaleDepart;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("api/connexion")
 */
class ApiConnexionController extends ApiController
{

    /**
     * @Route("", name="connexion_getAll", methods={"GET"})
     * @return JsonResponse
     */
    public function getAll()
    {
        return $this->json($this->repoConnexion->findAll(), 200, [], ['groups' => 'apiExport']);
    }


    /**
     * @Route("", name="api_connexion_create", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $connexion = $this -> setField($data);
        try {
            $this->emi->persist($connexion);
        } catch (Exception $e) {
            return $this->json($e->getMessage(), 200, [], ['groups' => 'apiExport']);
        }
        $this->emi->flush();
        return $this->json($connexion, 200, [], ['groups' => 'apiExport']);
    }




    /**
     * @Route("/deconnexion", name="api_deconnexion_update", methods={"GET"})
     * @param Request $request
     * @param Connexion $connexion
     * @return JsonResponse
     */
    public function deconnexion()
    {
        /** @var User $utilisateur */ 
        $utilisateur = $this->getUtilisateurConnecter();

        /** @var Connexion $connexion */
        $connexion = $this->repoConnexion->findLastConnexionUser($utilisateur->getId());
        $connexion->setDateDeconnexion(new \Datetime());
        $connexion->setZone(null);
      

        try {
            $this->emi->persist($connexion);
        } catch (Exception $e) {
            return $this->json($e->getMessage(), 200, [], ['groups' => 'apiExport']);
        }
        $this->emi->flush();
        return $this->json($connexion, 200, [], ['groups' => 'apiExport']);
    }


    /**
     * @Route("/{id}", name="api_connexion_getOne", methods={"GET"})
     * @param Connexion $connexion
     * @return JsonResponse
     */
    public function getOne(Connexion $connexion)
    {
        if(!$connexion){
            return $this->apiError("Connexion non trouvé");
        }

        $connexion = $this -> repoConnexion ->find($connexion->getId());

        return $this->json($connexion, 200, [], ['groups' => 'apiExport']);
    }


    /**
     * @Route("/{id}", name="api_connexion_delete",  methods={"DELETE"})
     * @param Connexion $connexion
     * @return JsonResponse|RedirectResponse
     */
    public function delete(Connexion $connexion)
    {
        if (!$connexion) {
            return $this->apiError("Connexion non trouvé");
        }

        $resultat =  $this->removeElement($connexion);
        return $this->json($resultat, 200, [], []);
    }


    /**
     * Modification de template
     * @param $data
     * @return Connexion
     */
    private function setField($data): Connexion {

        if(array_key_exists("id", $data)){
            $connexion = $this -> repoConnexion ->find($data["id"]);
        }else{
            $connexion = new Connexion();
            $connexion->setDateConnexion(new \Datetime());
        }

        $connexion->setUtilisateur($this->getUtilisateurConnecter());
        
        if($data["zone"]){
            /** @var Zone $zone */
            $zone = $this->repoZone->find($data["zone"]["id"]);
            $connexion->setZone($zone);
        }

        return $connexion;
    }




}
