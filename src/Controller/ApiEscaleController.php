<?php

namespace App\Controller;
use App\Controller\User;
use App\Entity\Escale;
use App\Entity\Passager;
use App\Entity\EscaleDepart;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/escale")
 */
class ApiEscaleController extends ApiController
{

    /**
     * @Route("", name="api_escale_getAll", methods={"GET"})
     * @return JsonResponse
     */
    public function getAll()
    {
        return $this->json($this->repoEscale->findAll(), 200, [], ['groups' => 'apiExport']);
    }


    /**
     * @Route("", name="api_escale_create", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $escale = $this -> setField($data);
        try {
            $this->emi->persist($escale);
        } catch (Exception $e) {
            return $this->json($e->getMessage(), 200, [], ['groups' => 'apiExport']);
        }
        $this->emi->flush();
        return $this->json($escale, 200, [], ['groups' => 'apiExport']);
    }




    /**
     * @Route("/{id}", name="api_escale_update", methods={"PATCH"})
     * @param Request $request
     * @param Escale $escale
     * @return JsonResponse
     */
    public function update(Request $request, Escale $escale)
    {

        if(!$escale){
            return $this->apiError("Escale non trouvé");
        }
        $data = json_decode($request->getContent(), true);

        $escale = $this -> setField($data);
        try {
            $this->emi->persist($escale);
        } catch (Exception $e) {
            return $this->json($e->getMessage(), 200, [], ['groups' => 'apiExport']);
        }
        $this->emi->flush();
        return $this->json($escale, 200, [], ['groups' => 'apiExport']);
    }


    /**
     * @Route("/{id}", name="api_escale_getOne", methods={"GET"})
     * @param Escale $escale
     * @return JsonResponse
     */
    public function getOne(Escale $escale)
    {
        if(!$escale){
            return $this->apiError("Escale non trouvé");
        }

        $escale = $this -> repoEscale ->find($escale->getId());

        return $this->json($escale, 200, [], ['groups' => 'apiExport']);
    }


    /**
     * @Route("/{id}", name="api_escale_delete",  methods={"DELETE"})
     * @param Escale $escale
     * @return JsonResponse|RedirectResponse
     */
    public function delete(Escale $escale)
    {
        if (!$escale) {
            return $this->apiError("Escale non trouvé");
        }

        $resultat =  $this->removeElement($escale);
        return $this->json($resultat, 200, [], []);
    }


    /**
     * Modification de template
     * @param $data
     * @return Escale
     */
    private function setField($data): Escale {
        if(array_key_exists("id", $data)){
            $escale = $this -> repoEscale ->find($data["id"]);
        }else{
            $escale = new Escale();
        }

        $escale->setNom($data['nom']);
        $escale->setIndiceAeroport($data['indiceAeroport']);
        $escale->setAeroport($data['aeroport']);

        return $escale;
    }

}
