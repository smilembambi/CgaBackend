<?php

namespace App\Controller;
use App\Controller\User;
use App\Entity\Service;
use App\Entity\Passager;
use App\Entity\EscaleDepart;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/service")
 */
class ApiServiceController extends ApiController
{

    /**
     * @Route("", name="api_service_getAll", methods={"GET"})
     * @return JsonResponse
     */
    public function getAll()
    {
        return $this->json($this->repoService->findAll(), 200, [], ['groups' => 'apiExport']);
   
    }


    /**
     * @Route("", name="api_service_create", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $service = $this -> setField($data);
        try {
            $this->emi->persist($service);
        } catch (Exception $e) {
            return $this->json($e->getMessage(), 200, [], ['groups' => 'apiExport']);
        }
        $this->emi->flush();
        return $this->json($service, 200, [], ['groups' => 'apiExport']);
    }




    /**
     * @Route("/{id}", name="api_service_update", methods={"PATCH"})
     * @param Request $request
     * @param Service $service
     * @return JsonResponse
     */
    public function update(Request $request, Service $service)
    {

        if(!$service){
            return $this->apiError("Service non trouvé");
        }
        $data = json_decode($request->getContent(), true);

        $service = $this -> setField($data);
        try {
            $this->emi->persist($service);
        } catch (Exception $e) {
            return $this->json($e->getMessage(), 200, [], ['groups' => 'apiExport']);
        }
        $this->emi->flush();
        return $this->json($service, 200, [], ['groups' => 'apiExport']);
    }


    /**
     * @Route("/{id}", name="api_service_getOne", methods={"GET"})
     * @param Service $service
     * @return JsonResponse
     */
    public function getOne(Service $service)
    {
        if(!$service){
            return $this->apiError("Service non trouvé");
        }

        $service = $this -> repoService ->find($service->getId());

        return $this->json($service, 200, [], ['groups' => 'apiExport']);
    }


    /**
     * @Route("/{id}", name="api_service_delete",  methods={"DELETE"})
     * @param Service $service
     * @return JsonResponse|RedirectResponse
     */
    public function delete(Service $service)
    {
        if (!$service) {
            return $this->apiError("Service non trouvé");
        }

        $resultat =  $this->removeElement($service);
        return $this->json($resultat, 200, [], []);
    }


    /**
     * Modification de template
     * @param $data
     * @return Service
     */
    private function setField($data): Service {

        if(array_key_exists("id", $data)){
            $service = $this -> repoService ->find($data["id"]);
        }else{
            $service = new Service();
        }
        $service->setNom($data['nom']);
        return $service;
    }




}
