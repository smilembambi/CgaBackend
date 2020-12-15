<?php

namespace App\Controller;
use App\Controller\User;
use App\Entity\Vol;
use App\Entity\Passager;
use App\Entity\Escale;
use App\Entity\EscaleDepart;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/vol")
 */
class ApiVolController extends ApiController
{

    /**
     * @Route("", name="api_vol_getAll", methods={"GET"})
     * @return JsonResponse
     */
    public function getAll()
    {
        $vols = $this->repoVol->findByEscale($this->getUtilisateurConnecter()->getEscale()->getId());
        $volsNoEscale = $this->repoVol->findByNoEscale($this->getUtilisateurConnecter()->getEscale()->getId());
       
       
        $result = array_merge($vols, $volsNoEscale); 
        return $this->json($result, 200, [], ['groups' => 'apiExport']);
    }


    /**
    * @Route("/find", name="api_vol_find", methods={"POST"})
    * @param Request $request
    * @return JsonResponse
    */
    public function find(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        /** @var User $user */
        $user = $this->getUtilisateurConnecter();
        

        $sel = "SELECT vol 
                FROM " . Vol::class . " AS vol 
                LEFT JOIN " . Escale::class . " as escale WITH escale.id = vol.depart";

       // Bagage dont depart est l'escale de l'utilisateur
        $query = $this->emi->createQuery($sel);
        $vols = $query->getResult();

        return $this->json($vols, 200, [], ['groups' => 'apiExport']);
    }



    /**
     * @Route("", name="api_vol_create", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $vol = $this -> setField($data);
        try {
            $this->emi->persist($vol);
        } catch (Exception $e) {
            return $this->json($e->getMessage(), 200, [], ['groups' => 'apiExport']);
        }
        $this->emi->flush();
        return $this->json($vol, 200, [], ['groups' => 'apiExport']);
    }




    /**
     * @Route("/{id}", name="api_vol_update", methods={"PATCH"})
     * @param Request $request
     * @param Vol $vol
     * @return JsonResponse
     */
    public function update(Request $request, Vol $vol)
    {

        if(!$vol){
            return $this->apiError("Vol non trouvé");
        }
        $data = json_decode($request->getContent(), true);

        $vol = $this -> setField($data);
        try {
            $this->emi->persist($vol);
        } catch (Exception $e) {
            return $this->json($e->getMessage(), 200, [], ['groups' => 'apiExport']);
        }
        $this->emi->flush();
        return $this->json($vol, 200, [], ['groups' => 'apiExport']);
    }


    /**
     * @Route("/{id}", name="api_vol_getOne", methods={"GET"})
     * @param Vol $vol
     * @return JsonResponse
     */
    public function getOne(Vol $vol)
    {
        if(!$vol){
            return $this->apiError("Vol non trouvé");
        }

        $vol = $this -> repoVol ->find($vol->getId());

        return $this->json($vol, 200, [], ['groups' => 'apiExport']);
    }


    /**
     * @Route("/{id}", name="api_vol_delete",  methods={"DELETE"})
     * @param Vol $vol
     * @return JsonResponse|RedirectResponse
     */
    public function delete(Vol $vol)
    {
        if (!$vol) {
            return $this->apiError("Vol non trouvé");
        }

        $resultat =  $this->removeElement($vol);
        return $this->json($resultat, 200, [], []);
    }


    /**
     * Modification de template
     * @param $data
     * @return Vol
     */
    private function setField($data): Vol {

        if(array_key_exists("id", $data)){
            $vol = $this -> repoVol ->find($data["id"]);
        }else{
            $vol = new Vol();
        }

        $vol->setNumeroVol($data['numeroVol']);

        if($data['depart']){
            /** @var Escale escaleDepart **/
            $escaleDepart = $this->repoEscale->find($data['depart']['id']);
            $vol->setDepart($escaleDepart);
        }

       if($data['arrive']){
            /** @var Escale $escaleArrive **/
            $escaleArrive = $this->repoEscale->find($data['arrive']['id']);
            $vol->setArrive($escaleArrive);
        }

        return $vol;
    }




}
