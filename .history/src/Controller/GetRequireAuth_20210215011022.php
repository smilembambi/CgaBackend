<?php

namespace App\Controller;
use App\Controller\User;
use App\Entity\Zone;
use App\Entity\Passager;
use App\Entity\EscaleDepart;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("api/zone")
 */
class GetRequireAuth extends ApiController
{

    /**
     * @Route("", name="zone_getAll", methods={"GET"})
     * @return JsonResponse
     */
    public function getAll()
    {
        return $this->json($this->repoZone->findAll(), 200, [], ['groups' => 'apiExport']);
    }


    /**
     * @Route("", name="api_zone_create", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $zone = $this -> setField($data);
        try {
            $this->emi->persist($zone);
        } catch (Exception $e) {
            return $this->json($e->getMessage(), 200, [], ['groups' => 'apiExport']);
        }
        $this->emi->flush();
        return $this->json($zone, 200, [], ['groups' => 'apiExport']);
    }




    /**
     * @Route("/{id}", name="api_zone_update", methods={"PATCH"})
     * @param Request $request
     * @param Zone $zone
     * @return JsonResponse
     */
    public function update(Request $request, Zone $zone)
    {

        if(!$zone){
            return $this->apiError("Zone non trouvé");
        }
        $data = json_decode($request->getContent(), true);

        $zone = $this -> setField($data);
        try {
            $this->emi->persist($zone);
        } catch (Exception $e) {
            return $this->json($e->getMessage(), 200, [], ['groups' => 'apiExport']);
        }
        $this->emi->flush();
        return $this->json($zone, 200, [], ['groups' => 'apiExport']);
    }


    /**
     * @Route("/{id}", name="api_zone_getOne", methods={"GET"})
     * @param Zone $zone
     * @return JsonResponse
     */
    public function getOne(Zone $zone)
    {
        if(!$zone){
            return $this->apiError("Zone non trouvé");
        }

        $zone = $this -> repoZone ->find($zone->getId());

        return $this->json($zone, 200, [], ['groups' => 'apiExport']);
    }


    /**
     * @Route("/{id}", name="api_zone_delete",  methods={"DELETE"})
     * @param Zone $zone
     * @return JsonResponse|RedirectResponse
     */
    public function delete(Zone $zone)
    {
        if (!$zone) {
            return $this->apiError("Zone non trouvé");
        }

        $resultat =  $this->removeElement($zone);
        return $this->json($resultat, 200, [], []);
    }


    /**
     * Modification de template
     * @param $data
     * @return Zone
     */
    private function setField($data): Zone {

        if(array_key_exists("id", $data)){
            $zone = $this -> repoZone ->find($data["id"]);
        }else{
            $zone = new Zone();
        }

        return $zone;
    }




}
