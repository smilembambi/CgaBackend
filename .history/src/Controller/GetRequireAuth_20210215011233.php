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
 * @Route("getrequireauth")
 */
class GetRequireAuth extends ApiController
{

    /**
     * @Route("", name="getRequiteAuth", methods={"GET"})
     * @return JsonResponse
     */
    public function getRequire()
    {   
        $zone = $this->repoZone->findAll();
        $escale = $this->repoEscale->findAll();

        $data = [
            "zone"=> $zone;
            "escale"=>$escale
        ]
        return $this->json($data, 200, [], ['groups' => 'apiExport']);
    }


}
