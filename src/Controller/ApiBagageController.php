<?php

namespace App\Controller;
use App\Controller\User;
use App\Entity\Bagage;
use App\Entity\Passager;
use App\Entity\Zone;
use App\Entity\Connexion;
use App\Entity\EscaleDepart;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/bagage")
 */
class ApiBagageController extends ApiController
{

    /**
     * @Route("", name="api_bagage_getAll", methods={"GET"})
     * @return JsonResponse
     */
    public function getAll()
    {
        return $this->json($this->repoBagage->findAll(), 200, [], ['groups' => 'apiExport']);
    }


    /**
     * @Route("", name="api_bagage_create", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if(empty($data['codeBarre'])){
            return $this->json("Pas de code barre", 200, [], ['groups' => 'apiExport']);
        }


       /** @var User $user */
       $utilisateur = $this->getUtilisateurConnecter();

        /** @var Connexion $connexion */
        $connexion = $this->repoConnexion->findLastConnexionUser($utilisateur->getId());

       /** @var Zone $zone */
       $zone = $connexion->getZone();

        /** @var Bagage $dejaScanner **/
        $dejaScanner = $this->repoBagage->findOneBy(['codeBarre'=>$data["codeBarre"],'statut'=>$zone]);
        if($dejaScanner){
           return $this->json("dejaScanner", 200, [], ['groups' => 'apiExport']);
        }


        $bagage = $this -> setField($data);
        try {
            $this->emi->persist($bagage);
        } catch (Exception $e) {
            return $this->json($e->getMessage(), 200, [], ['groups' => 'apiExport']);
        }
        $this->emi->flush();
        return $this->json($bagage, 200, [], ['groups' => 'apiExport']);
    }



        /**
         * @Route("/tag", name="api_bagage_tag", methods={"POST"})
         * @param Request $request
         * @return JsonResponse
         */
        public function createTagManuel(Request $request)
        {
            $data = json_decode($request->getContent(), true);

            if(empty($data['codeBarre'])){
                return $this->json("Pas de code barre", 200, [], ['groups' => 'apiExport']);
            }


           /** @var User $user */
           $utilisateur = $this->getUtilisateurConnecter();

            /** @var Connexion $connexion */
            $connexion = $this->repoConnexion->findLastConnexionUser($utilisateur->getId());

             file_put_contents("data.txt",$connexion->getId());

           /** @var Zone $zone */
           $zone = $connexion->getZone();

            /** @var Bagage $dejaScanner **/
            $dejaScanner = $this->repoBagage->findOneBy(['codeBarre'=>$data["codeBarre"],'statut'=>$zone]);

            if($dejaScanner){
               return $this->json("dejaScanner", 200, [], ['groups' => 'apiExport']);
            }


            $bagage = new Bagage();
            $bagage->setCodeBarre($data["codeBarre"]);
            $escaleArrive = $this->repoEscale->find($data["escaleArrive"]["id"]);
            $bagage->setEscaleArrive($escaleArrive);

            $vol = $this->repoVol->find($data["vol"]["id"]);
            $bagage->setVol($vol);
            $bagage->setPoids($data['poids']);

           $escaleDepart = $utilisateur->getEscale();
           $bagage->setEscaleDepart($escaleDepart);
           $bagage->setTagManuel(true);



        if($zone->getSlug() == "CC"){
            $bagage->setAgentChargementContenaire($utilisateur);
            $bagage->setDateChargementContenaire(new \Datetime());
        }

        if($zone->getSlug() == "CS"){
            $bagage->setAgentChargementSoute($utilisateur);
            $bagage->setDateChargementSoute(new \Datetime());
        }

        if($zone->getSlug() == "DC"){
            $bagage->setAgentDechargementContenaire($utilisateur);
            $bagage->setDateDechargementContaire(new \Datetime());
            $bagage->setStatut("DC");
        }

        if($zone->getSlug() == "DS"){
            $bagage->setAgentDechargementSoute($utilisateur);
            $bagage->setDateDechargementSoute(new \Datetime());
        }

        if($zone->getSlug() == "LB"){
            $bagage->setAgentLivraison($utilisateur);
            $bagage->setDateLivraison(new \Datetime());
        }

        if($zone->getSlug()){
            /** @var Zone $zone **/
            $zone = $this->repoZone->findOneBySlug($zone->getSlug());
            $bagage->setStatut($zone);
        }


            try {
                $this->emi->persist($bagage);
            } catch (Exception $e) {
                return $this->json($e->getMessage(), 200, [], ['groups' => 'apiExport']);
            }
            $this->emi->flush();
            return $this->json($bagage, 200, [], ['groups' => 'apiExport']);
        }


     /**
     * @Route("/upload", name="api_bagage_upload", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function upload(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if(!empty($data)){

            $bagages = array();
            for($i = 0; $i < sizeOf($data); $i++){
                /** @var Bagage $bagage */
                $bagage = $this->repoBagage->findCodeBarre($data[$i]['codeBarre']);

                if($bagage){
                    /** @var Escale $escaleDepart */
                    $escaleDepart = $bagage->getEscaleDepart();

                    if($escaleDepart){
                        if($escaleDepart->getIndiceAeroport() != $data[$i]['escaleDepart']['indiceAeroport']){
                            $bagage->setEstEnDetresse(true);

                           /** @var Zone $zone **/
                           $zone = $this->repoZone->findOneBySlug('ED');
                           $bagage->setStatut($zone);
                           $bagage->setOrigineDetresse("Escale différent");
                        }
                    }else{
                         /** @var Escale $escaleDepart */
                         $escaleDepart = $this->repoEscale->findOneByIndiceAeroport($data[$i]['escaleDepart']['indiceAeroport']);
                         $bagage->setEscaleDepart($escaleDepart);
                    }
                }else{
                    /** @var Bagage $bagage */
                    $bagage = new Bagage();
                    $bagage->setEstEnDetresse(true);
                    if($data[$i]['codeBarre'] == ""){
                        /** @var Zone $zone **/
                        $zone = $this->repoZone->findOneBySlug('PB');
                    }else{
                        /** @var Zone $zone **/
                        $zone = $this->repoZone->findOneBySlug('NT');
                    }
                    $bagage->setCodeBarre($data[$i]['codeBarre']);
                    $bagage->setOrigineDetresse("Non scanné");
                    $bagage->setStatut($zone);
                    
                    /** @var Escale $escaleDepart */
                    $escaleDepart = $this->repoEscale->findOneByIndiceAeroport($data[$i]['escaleDepart']['indiceAeroport']);
                    $bagage->setEscaleDepart($escaleDepart);
                }

                /** @var Passager $passager */
                $passager = $this->repoPassager->findOneBy(['nom'=>$data[$i]['passager']['nom']]);

                if(!$passager){
                    /** @var Passager $passager */
                    $passager = new Passager();
                    $passager->setNom($data[$i]['passager']['nom']);
                    $passager->setPrenom("");
                    $passager->setTicket($data[$i]['passager']['ticket']);
                    $passager->setSiege($data[$i]['passager']['siege']);
                    $passager->setPnr($data[$i]['passager']['pnr']);
                    $passager->setFranchiseDemande($data[$i]['passager']['franchiseDemande']);
                    $passager->setFranchiseDisponible($data[$i]['passager']['franchiseDisponible']);

                    if(isset($data[$i]['passager']['excedant'])){
                        $passager->setExcedant($data[$i]['passager']['excedant']);
                    }
                   
                    $this->emi->persist($passager);
                    $this->emi->flush();
                }

                $bagage->setPassager($passager);
                //$bagage->setPoids($data[$i]['poids']);

                /** @var Escale $escaleArrive */  
                $escaleArrive = $this->repoEscale->findOneByIndiceAeroport($data[$i]['escaleArrive']['indiceAeroport']);
                $bagage->setEscaleArrive($escaleArrive);
                
                $bagage->setAgentUploadManisfet($this->getUtilisateurConnecter());
                $bagage->setDateUpload(new \Datetime());
                
                /** @var Vol $vol */  
                $vol = $this->repoVol->find($data[$i]['vol']['id']);
                $bagage->setVol($vol);

                $bagage->setManifestCharge(true);
                $bagage->setPoids($data[$i]['poids']);
                $bagage->setTagManuel(false);

                try {
                    $this->emi->persist($bagage);
                } catch (Exception $e) {
                    return $this->json($e->getMessage(), 200, [], ['groups' => 'apiExport']);
                }
                $this->emi->flush();
                array_push($bagages, $bagage);
            }

            
        }
        
        
        return $this->json($bagages, 200, [], ['groups' => 'apiExport']);
    }



    /**
    * @Route("/find", name="api_bagage_find", methods={"POST"})
    * @param Request $request
    * @return JsonResponse
    */
    public function find(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        
        if(array_key_exists("date", $data)){
            $date = $data['date'];
        }else{
            $date = new \Datetime();
            $date = $date->format('Y-m-d');
        }

        $from = date("Y-m-d H:i:s", strtotime($date . " 00:00:00"));
        $to = date("Y-m-d H:i:s", strtotime($date . " 23:59:59"));
      

        $sel = "SELECT bagage 
                FROM " . Bagage::class . " AS bagage 
                LEFT JOIN " . Passager::class . " as passager WITH passager.id = bagage.passager
                WHERE (bagage.dateChargementContenaire between '" . $from . "' AND '" . $to . "'
                OR bagage.dateUpload between '" . $from . "' AND '" . $to . "'
                OR bagage.dateChargementSoute between '" . $from . "' AND '" . $to . "'
                OR bagage.dateLivraison between '" . $from . "' AND '" . $to . "' ) " ;
        
        // Escale depart
        if($data['escaleDepart'] != 0){
            $sel .= " AND bagage.escaleDepart = " . $data['escaleDepart'];
        }


        //Escale arriver
        if($data['escaleArrive'] != 0){
            $sel .= " AND bagage.escaleArrive = " . $data['escaleArrive'];
        }


        //Vol
        if($data['vol'] != 0){
            $sel .= " AND bagage.vol = " . $data['vol'];
        }

        //Passager
        if(array_key_exists("passager", $data)){
            $sel .= " AND (passager.nom like '%".$data['passager']."%') ";
        }

        //Tag
        if(array_key_exists("tag", $data)){
            $sel .= " AND bagage.codeBarre like '%" . $data['tag'] . "'";
        }

        file_put_contents("data.txt",$sel);

       // Bagage dont depart est l'escale de l'utilisateur
        $query = $this->emi->createQuery($sel);
        $bagages = $query->getResult();

        return $this->json($bagages, 200, [], ['groups' => 'apiExport']);
    }


    /**
     * @Route("/{id}", name="api_bagage_update", methods={"PATCH"})
     * @param Request $request
     * @param Bagage $bagage
     * @return JsonResponse
     */
    public function update(Request $request, Bagage $bagage)
    {

        if(!$bagage){
            return $this->apiError("Bagage non trouvé");
        }
        $data = json_decode($request->getContent(), true);




        $bagage = $this -> setField($data);
        try {
            $this->emi->persist($bagage);
        } catch (Exception $e) {
            return $this->json($bagage, 200, [], ['groups' => 'apiExport']);
        }
        $this->emi->flush();
        return $this->json($bagage, 200, [], ['groups' => 'apiExport']);
    }


    /**
     * @Route("/{id}", name="api_bagage_getOne", methods={"GET"})
     * @param Bagage $bagage
     * @return JsonResponse
     */
    public function getOne(Bagage $bagage)
    {
        if(!$bagage){
            return $this->apiError("Bagage non trouvé");
        }

        $bagage = $this -> repoBagage ->find($bagage->getId());

        return $this->json($bagage, 200, [], ['groups' => 'apiExport']);
    }


    /**
     * @Route("/{id}", name="api_bagage_delete",  methods={"DELETE"})
     * @param Bagage $bagage
     * @return JsonResponse|RedirectResponse
     */
    public function delete(Bagage $bagage)
    {
        if (!$bagage) {
            return $this->apiError("Bagage non trouvé");
        }

        $resultat =  $this->removeElement($bagage);
        return $this->json($resultat, 200, [], []);
    }


    /**
     * Modification de template
     * @param $data
     * @return Bagage
     */
    private function setField($data){


        /** @var User $user */
        $utilisateur = $this->getUtilisateurConnecter();

        /** @var Connexion $connexion */
        $connexion = $this->repoConnexion->findLastConnexionUser($utilisateur->getId());

        /** @var Zone $zone */
        $zone = $connexion->getZone();

        $bagage = $this->repoBagage->findOneBy(['codeBarre'=>$data["codeBarre"]]);
        if(!$bagage){   
            $bagage = new Bagage();
        }


        if($zone->getSlug() == "CC"){
            $bagage->setAgentChargementContenaire($utilisateur);
            $bagage->setDateChargementContenaire(new \Datetime());
        }

        if($zone->getSlug() == "CS"){
            $bagage->setAgentChargementSoute($utilisateur);
            $bagage->setDateChargementSoute(new \Datetime());
        }

        if($zone->getSlug() == "DC"){
            $bagage->setAgentDechargementContenaire($utilisateur);
            $bagage->setDateDechargementContaire(new \Datetime());
            $bagage->setStatut("DC");
        }

        if($zone->getSlug() == "DS"){
            $bagage->setAgentDechargementSoute($utilisateur);
            $bagage->setDateDechargementSoute(new \Datetime());
        }

        if($zone->getSlug() == "LB"){
            $bagage->setAgentLivraison($utilisateur);
            $bagage->setDateLivraison(new \Datetime());
        }

        if($zone->getSlug()){
            /** @var Zone $zone **/
            $zone = $this->repoZone->findOneBySlug($zone->getSlug());
            $bagage->setStatut($zone);
        }

        $bagage->setCodeBarre($data['codeBarre']);
 
        return $bagage;
    }




}
