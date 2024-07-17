<?php

namespace App\Controller;

use App\Entity\Alliance;
use App\Entity\Position;
use App\Entity\Serveur;
use App\Form\AjoutPositionType;
use App\Form\AllianceType;
use App\Form\PositionServeurType;
use App\Form\PositionType;
use App\Form\ServeurType;
use App\Repository\PositionRepository;
use App\Repository\ServeurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/')]
class ServeurController extends AbstractController
{
    private $largeur = 90;
    private $hauteur = 60;
    private $xmin = 1;
    private $ymin = 1;
    private $xmax = 90*20;
    private $ymax = 60*20;

    private $image;

    #[Route('/', name: 'app_serveur_index', methods: ['GET'])]
    public function index(ServeurRepository $serveurRepository): Response
    {
        return $this->render('serveur/index.html.twig', [
            'serveurs' => $serveurRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_serveur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $serveur = new Serveur();
        $serveur->setDebut(new \DateTime());
        $form = $this->createForm(ServeurType::class, $serveur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($serveur);
            $entityManager->flush();

            return $this->redirectToRoute('app_serveur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('serveur/new.html.twig', [
            'serveur' => $serveur,
            'form' => $form,
        ]);
    }

    #[Route('/{numero}', name: 'app_serveur_show', methods: ['GET', 'POST'])]
    public function show(Request $request,Serveur $serveur, EntityManagerInterface $entityManager, PositionRepository $positionRepository): Response
    {
        $position = new Position();
//        $position->setMoment();
        $position->setDuree(\DateInterval::createFromDateString('0 day'));
        $position->setServeur($serveur);
        $form = $this->createForm(PositionServeurType::class, $position);
        $form
//SUIVI
            ->add('ajout', SubmitType::class, ['label' => 'Nouvelle capture', 'attr' => ['class' => 'btn btn-success']])
            ->add('maj', SubmitType::class, ['label' => 'Mise à jour position', 'attr' => ['class' => 'btn btn-primary']])
            ->add('suppr', SubmitType::class, ['label' => 'Supprimer position', 'attr' => ['class' => 'btn btn-danger']])
//STRATEGIE
            ->add('attaque', SubmitType::class, ['label' => 'attaque', 'attr' => ['class' => 'btn btn-danger']])
            ->add('defense', SubmitType::class, ['label' => 'défense', 'attr' => ['class' => 'btn btn-warning']])
            ->add('immunite', SubmitType::class, ['label' => 'immunité', 'attr' => ['class' => 'btn btn-primary']]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getClickedButton()) {
                $action = $form->getClickedButton()->getName();
                $autre_position = $positionRepository->findDernierePositionv2($position->getPosX(),$position->getPosY());

                switch ($action){
                    //suivi
                    case 'ajout': //on a une nouvelle position
                        $dateCapture = new \DateTime('now');
                        if (!$position->getDuree()) $this->addFlash('warning','pas de données sur le bouclier');
                        else $dateCapture->sub($position->getDuree());
                        $position->setMoment($dateCapture);
                        $entityManager->persist($position);
                        $this->addFlash('info','Ajout de la position : alliance'.$position->getAlliance()." a pris le controle de ".$position." a la date du ".$position->getMoment()->format("d/m/y H:i"));
                        break;
                    case 'ajout': //on a une nouvelle position
                        $entityManager->remove($position);
                        $this->addFlash('danger', 'Suppression de la position');
                        break;
                    case 'maj':
                        $autre_position->setAlliance($position->getAlliance());
                        $this->addFlash('warning','Changement d\'alliance !');
                        break;
                    //Stratégie, on met à jour la position existante
                    case 'attaque':
                        if ($autre_position->getCible() == "attaque"){
                            $autre_position->setCible(null);
                            $this->addFlash('warning','Suppression de l\'attaque !');
                        }
                        else {
                            $this->addFlash('warning','Planification de l\'attaque !');
                            $autre_position->setCible('attaque');
                        }

                        break;
                    case 'defense':
                        if ($autre_position->getCible() == "défense"){
                            $autre_position->setCible(null);
                            $this->addFlash('warning','Suppression de la défense !');
                        }
                        else {
                            $this->addFlash('warning','Planification de la défense !');
                            $autre_position->setCible('défense');
                        }

                        break;
                    case 'immunite':
                        if ($autre_position->getCible() == "immunité"){
                            $autre_position->setCible(null);
                            $this->addFlash('warning','Suppression de l\'immunité !');
                        }
                        else {
                            $this->addFlash('warning','Planification de l\'immunité !');
                            $autre_position->setCible('immunité');
                        }

                        break;

                    default:
                        if ($position->getAlliance()->getId() == $autre_position->getAlliance()->getId()){
                            $entityManager->remove($autre_position);
                            $this->addFlash('danger', 'Suppression de la position');
                        }
                        else {
                            $autre_position->setAlliance($position->getAlliance());
                            $this->addFlash('warning','Changement d\'alliance !');
                        }
                }
            }
            else {
                $this->addFlash('warning','clic non detecté !');

            }

//            if ($autre_position) {
////                $this->addFlash('warning', 'cette position est déjà attribuée');
//
//
//
//            }
//            else {
//                //Nouvelle position
//
//                $entityManager->persist($position);
//                $this->addFlash('info','Ajout de la position');
//
//            }
            $entityManager->flush();
//
//            return $this->redirectToRoute('app_position_index', [], Response::HTTP_SEE_OTHER);
        }


        return $this->render('serveur/show.html.twig', [
            'serveur' => $serveur,
            'form' => $form,
            'largeur'  => $this->largeur,
            'hauteur'  => $this->hauteur

        ]);
    }

    #[Route('/{numero}/edit', name: 'app_serveur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Serveur $serveur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ServeurType::class, $serveur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_serveur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('serveur/edit.html.twig', [
            'serveur' => $serveur,
            'form' => $form,
        ]);
    }

    #[Route('/{numero}/delete', name: 'app_serveur_delete', methods: ['POST'])]
    public function delete(Request $request, Serveur $serveur, EntityManagerInterface $entityManager): Response
    {
//        $this->addFlash('info','tentative de suppression');
        if ($this->isCsrfTokenValid('delete'.$serveur->getId(), $request->getPayload()->getString('_token'))) {
            $this->addFlash('danger','suppression du serveur !')    ;
            $entityManager->remove($serveur);
            $entityManager->flush();
        }
        else $this->addFlash('warning','erreur CSRF, pas de suppression');
        return $this->redirectToRoute('app_serveur_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/{numero}/map', name: 'app_serveur_map', methods: ['GET'])]
    public function map(Serveur $serveur,PositionRepository $positionRepository, LoggerInterface $logger): Response
    {
        $imagine = new Imagine();


//        $image = $imagine->open('images/Season1.png');
//        $image = imagecreatefrompng('images/Season1.png');
        $this->makemapvierge();
$today = new \DateTime('now');
        //imagescale($image,800);
        $image = $this->image;
        for ($ligne = 1; $ligne < 21; $ligne++) {
            for ($colonne = 1; $colonne < 21; $colonne++) {
//                findDernierePositionv2
                $position= $positionRepository->findDernierePositionv2($colonne, $ligne);
                if ($position) {
                    $x = $this->conversionX($position->getPosX());
                    $y = $this->conversionY($position->getPosX(),$position->getPosY());
//          $this->addFlash('warning',' x :'.$position->getPosX().' => '.$x);
                    for ($i = 1; $i < 5; $i++) {
                        //icone
                        imagerectangle($image,$x+$i,$y+$i,$x+$this->largeur-$i,$y+$this->hauteur-$i,$position->getAlliance()->getGDColor($image));
                        //          imageellipse($image, $position->getPosX(), $position->getPosY(), 60, 60, $position->getAlliance()->getCouleur());
                        //texte
                        imagestring($image, 5, $x+$this->largeur*0.85-18, $y+$this->hauteur/2-20, $position->getAlliance()->getNom(), $position->getAlliance()->getGDColor($image));
                        if ($position->getMoment()){
                          $ecart = $position->getMoment()->diff($today);
                           if ($ecart->invert == 1)    $temps = $ecart->format("%dj%Hh%I");
                            else $temps ="";
                            imagestring($image, 2, $x+$this->largeur*0.85-30, $y+$this->hauteur/2+10, $temps, imagecolorallocate($image, 255, 0, 0));
                        }

//            imagettftext($image, 20, 0, $position->getPosX()-20, $position->getPosY(), $position->getAlliance()->getGDColor($image),'arial', $position->getAlliance()->getNom());

                    }
                    if ($position->getCible()) $this->cible($position);
                }

            }
        }
//        foreach ($serveur->getPositions() as $position)
//        {
//            $x = $this->conversionX($position->getPosX());
//            $y = $this->conversionY($position->getPosX(),$position->getPosY());
////          $this->addFlash('warning',' x :'.$position->getPosX().' => '.$x);
//            for ($i = 1; $i < 5; $i++) {
//                 //icone
//                 imagerectangle($image,$x+$i,$y+$i,$x+$this->largeur-$i,$y+$this->hauteur-$i,$position->getAlliance()->getGDColor($image));
//                 //          imageellipse($image, $position->getPosX(), $position->getPosY(), 60, 60, $position->getAlliance()->getCouleur());
//                 //texte
//                 imagestring($image, 5, $x+$this->largeur*0.85-18, $y+$this->hauteur/2-20, $position->getAlliance()->getNom(), $position->getAlliance()->getGDColor($image));
//                 if ($position->getMoment()){
//
//                    if ($position->getMoment() > $today )$temps = $position->getMoment()->diff($today)->format("%dj%Hh%I");
//                    else $temps ="";
//                    imagestring($image, 2, $x+$this->largeur*0.85-30, $y+$this->hauteur/2+10, $temps, imagecolorallocate($image, 255, 0, 0));
//                 }
//
////            imagettftext($image, 20, 0, $position->getPosX()-20, $position->getPosY(), $position->getAlliance()->getGDColor($image),'arial', $position->getAlliance()->getNom());
//
//            }
//            if ($position->getCible()) $this->cible($position);
//
//
//
//        }

       //imagepng($image,'images/'.$serveur->getNumero().'.png');
//        $image ->save('images/'.$serveur->getNumero().'.png');


        return new StreamedResponse(fn () => imagepng($image), 200, ['Content-Type' => 'image/png']);
//        return $this->render('serveur/show.html.twig', [
//            'serveur' => $serveur,
//        ]);
    }


    #[Route('{numero}/alliance/new/', name: 'app_serveur_new_alliance', methods: ['GET', 'POST'])]
    public function new_alliance(Request $request, Serveur $serveur, EntityManagerInterface $entityManager): Response
    {
        $alliance = new Alliance();
        $alliance->setServeur($serveur);

        $form = $this->createForm(AllianceType::class, $alliance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($alliance);
            $entityManager->flush();

            return $this->redirectToRoute('app_serveur_show', ['numero' => $serveur->getNumero()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('alliance/new.html.twig', [
            'alliance' => $alliance,
            'form' => $form,
        ]);
    }
    #[Route('/{numero}/mapvierge', name: 'app_serveur_mapvierge', methods: ['GET'])]
    public function mapvierge(Serveur $serveur,LoggerInterface $logger): Response
    {

        $this->mapvierge();
        return new StreamedResponse(fn () => imagepng($this->image), 200, ['Content-Type' => 'image/png']);

    }
    private function makemapvierge()
    {
//        $largeur = 90;
//        $hauteur = 60;
//        $xmin = 40;
//        $ymin = 40;
//        $xmax = $largeur*20;
//        $ymax = $hauteur*20;

//        $this->image = $this->grille();



        $this->image = imagecreatetruecolor($this->xmax+2*$this->xmin,$this->ymax+2*$this->ymin);
        $background_color = imagecolorallocate($this->image , 255, 255, 255);
        imagefill($this->image, 0, 0, $background_color); // you have to actually use the allocated background color



        $this->grille();
        $this->remplirVille();

//        $image = $this->grille($largeur, $hauteur, $xmin, $ymin,$xmax,$ymax);



       }

    #[Route('{numero}/alliance/{id_alliance}/position/new/', name: 'app_serveur_alliance_position_add', methods: ['GET', 'POST'])]
    public function new_position(Request $request, Serveur $serveur,$id_alliance, EntityManagerInterface $entityManager): Response
    {

        $alliance = $entityManager->getRepository(Alliance::class)->find($id_alliance);
//        dump($alliance); die;
        $position = new Position();
        $position->setServeur($serveur);
        $position->setAlliance($alliance);

        $form = $this->createForm(AjoutPositionType::class, $position);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($position);
            $entityManager->flush();

            return $this->redirectToRoute('app_serveur_show', ['numero' => $serveur->getNumero()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('serveur/ajoutPosition.html.twig', [
            'position' => $position,
            'alliance' => $alliance,
            'serveur' => $serveur,
            'form' => $form,
        ]);
    }

    #[Route('/pos/{id}/delete', name: 'app_serveur_delete_pos')]
    public function deletePosition(Position $position, EntityManagerInterface $entityManager): Response
    {

        $numero = $position->getServeur()->getNumero();
        $this->addFlash('warning', "suppression d'une position de l'alliance ".$position->getAlliance());

            $entityManager->remove($position);
            $entityManager->flush();


        return $this->redirectToRoute('app_serveur_show', ['numero' => $numero], Response::HTTP_SEE_OTHER);
    }


    private function grille(){
        $largeur = $this->largeur;
        $hauteur = $this->hauteur;
        $xmin = $this->xmin;
        $ymin =$this->ymin;
        $xmax = $this->xmax;
        $ymax = $this->ymax;

        $colorWhite=imagecolorallocate($this->image, 255, 255, 255);
        $colorGrey=imagecolorallocate($this->image, 192, 192, 192);
        $colorBlue=imagecolorallocate($this->image, 0, 0, 255);
        $colorBlack=imagecolorallocate($this->image, 0, 0, 0);


//        imageline($image, $xmin, $ymin, $xmin, $ymax, $colorBlack);
//        imageline($image, $xmin, $ymin, $xmax, 0, $colorBlack);
//        imageline($image, 249, 0, 249, 249, $colorBlack);
//        imageline($image, 0, 249, 249, 249, $colorBlack);
        //le quadrillage
        for ($x=0; $x<21; $x++) {
            imageline($this->image, $x * $largeur + $xmin, $ymin, $x * $largeur + $xmin, $ymax + $ymin, $colorBlack);
            if ($x<20){
                if ($x % 2 === 0) {
                    for ($y=0; $y<21; $y++)
                    {
                        imageline($this->image, $x * $largeur + $xmin, $y * $hauteur + $ymin, ($x + 1) * $largeur + $xmin, $y * $hauteur + $ymin, $colorBlack);
                    }
                }
                else {
                    for ($y=0; $y<20; $y++) {
                        if ($y == 0) {
                            imageline($this->image, $x * $largeur + $xmin, ($y) * $hauteur + $ymin, ($x + 1) * $largeur + $xmin, ($y) * $hauteur + $ymin, $colorBlack);
                        } elseif ($y < 19) {
                            imageline($this->image, $x * $largeur + $xmin, ($y + 0.5) * $hauteur + $ymin, ($x + 1) * $largeur + $xmin, ($y + 0.5) * $hauteur + $ymin, $colorBlack);
                        } else imageline($this->image, $x * $largeur + $xmin, ($y + 1) * $hauteur + $ymin, ($x + 1) * $largeur + $xmin, ($y + 1) * $hauteur + $ymin, $colorBlack);
                    }
                }


            }
            imagefilledrectangle($this->image,9 * $largeur + $xmin, 9 * $hauteur + $ymin,11 * $largeur + $xmin, 11 * $hauteur + $ymin,$colorGrey);

        }
//        return $this->image;
    }

    private function ville($ville, $x, $y){


        $sx = imagesx($ville);
        $sy = imagesy($ville);
        imagecopymerge($this->image, $ville, $this->conversionX($x) , $this->conversionY($x,$y) , 0, 0, $sx, $sy, 75);

//        return $image;
    }

    private function conversionX($x)
    {
        return ($x-1)*$this->largeur + $this->xmin;
    }
    private function conversionY($x,$y)
    {
        $x--;
        $y--;
        if ($x<20){
            if ($x % 2 === 0) {

                  return $y * $this->hauteur + $this->ymin;

            }
            else {
                if ($y == 0)  return $this->ymin;
                elseif ($y < 19) return ($y + 0.5) * $this->hauteur + $this->ymin;
                    else return ($y ) * $this->hauteur + $this->ymin;

            }


        }
        return ($y-1)*$this->hauteur;// + $this->ymin;
    }

    private function cible($position){
        $x = $this->conversionX($position->getPosX());
        $y = $this->conversionY($position->getPosX(),$position->getPosY());
        $image = $this->image;


//        $logo_immu = imagecreatefrompng('images/immu.png');

        switch ($position->getCible()){
            case "attaque":
                $logo_attaque = imagecreatefrompng('images/attaque.png');
//                $this->resize('images/attaque.png',50, 50);
                imagecopymerge($image,$logo_attaque, $x+5, $y+5, 0,0, 50, 50,50);

                break;
            case "défense":
                $logo_bouclier = imagecreatefrompng('images/bouclier.png');
//                $this->resize('images/bouclier.png',50, 50);
                imagecopymerge($image,$logo_bouclier, $x+5, $y+5, 0,0, 50, 50,50);
                break;
            case "immunité":
                imageellipse($image, $x+30, $y+30, 60, 60, $position->getAlliance()->getGDColor($image));
                imageellipse($image, $x+30, $y+30, 40, 40, $position->getAlliance()->getGDColor($image));
                imageellipse($image, $x+30, $y+30, 20, 20, $position->getAlliance()->getGDColor($image));
//                imageellipse($image, $x+30, $y+30, 60, 60, $position->getAlliance()->getGDColor($image));

//                    imagecopy($image,$logo_immu, $position->getPosX()-50, $position->getPosY()-50, 0,0, 100, 100);
                break;
        }

    }
    private function remplirVille()
    {
        //VILLE 1
//        $this->resize('images/ville1.png');
//        $this->resize('images/1.PNG');
        $ville1 = imagecreatefrompng('images/1.PNG');
        for ($x = 1; $x <= 6; $x++) {
            $this->ville($ville1, $x * 3 + 1, 1);
            if ($x == 6) $this->ville($ville1, 20 - $x * 3 + 1, 20);
            else $this->ville($ville1, 20 - $x * 3, 20);
        }

        for ($y = 1; $y <= 6; $y++) {
            $this->ville($ville1, 1, $y * 3 + 1);
            $this->ville($ville1, 20, $y * 3);
        }

        //Ville 2
        $this->resize('images/2.PNG');
        $ville2 = imagecreatefrompng('images/2.PNG');
//        $ville2 = imagecreatefrompng('images/ville2.png');
        for ($x = 0; $x < 6; $x++) {
            $this->ville($ville2, $x * 2 + 5, 3);
            $this->ville($ville2, $x * 2 + 5, 18);
            if ($x == 2) $x++;
        }

        for ($y = 0; $y < 5; $y++) {
            $this->ville($ville2, 3, $y * 3 + 4);
            $this->ville($ville2, 18, $y * 3 + 4);
        }

        //Ville 3
//        $this->resize('images/ville3.png');
//        $ville3 = imagecreatefrompng('images/ville3.png');
        $this->resize('images/3.PNG');
        $ville3 = imagecreatefrompng('images/3.PNG');


        $this->ville($ville3, 8, 4);
        $this->ville($ville3, 10, 4);
        $this->ville($ville3, 12, 4);
        $this->ville($ville3, 14, 4);

        $this->ville($ville3, 8, 16);
        $this->ville($ville3, 10, 16);
        $this->ville($ville3, 12, 16);
        $this->ville($ville3, 14, 16);

        $this->ville($ville3, 5, 5);
        $this->ville($ville3, 5, 8);
        $this->ville($ville3, 5, 12);
        $this->ville($ville3, 5, 15);

        $this->ville($ville3, 16, 5);
        $this->ville($ville3, 16, 8);
        $this->ville($ville3, 16, 12);
        $this->ville($ville3, 16, 15);


        //Ville 4
        $this->resize('images/4.PNG');
        $ville4 = imagecreatefrompng('images/4.PNG');

        $this->ville($ville4, 9, 6);
        $this->ville($ville4, 11, 6);
        $this->ville($ville4, 13, 6);

        $this->ville($ville4, 9, 15);
        $this->ville($ville4, 11, 15);
        $this->ville($ville4, 13, 15);

        $this->ville($ville4, 7, 7);
        $this->ville($ville4, 7, 9);
        $this->ville($ville4, 7, 13);

        $this->ville($ville4, 14, 7);
        $this->ville($ville4, 14, 9);
        $this->ville($ville4, 14, 13);

        //Ville 5
        $this->resize('images/5.PNG');
        $ville5 = imagecreatefrompng('images/5.PNG');

        $this->ville($ville5, 9, 8);
        $this->ville($ville5, 8, 11);
        $this->ville($ville5, 12, 7);
        $this->ville($ville5, 13, 12);
        $this->ville($ville5, 10, 13);

        //Ville 6
        $this->resize('images/6.PNG');
        $ville5 = imagecreatefrompng('images/6.PNG');

        $this->ville($ville5, 12, 9);
        $this->ville($ville5, 9, 10);
        $this->ville($ville5, 11, 12);

        $this->resize('images/7.PNG');
        $ville7 = imagecreatefrompng('images/7.PNG');
        $sx = imagesx($ville7);
        $sy = imagesy($ville7);
        imagecopymerge($this->image, $ville7, $this->conversionX(10)+$this->largeur/2 +$sx/2 , $this->conversionY(10,10) , 0, 0, $sx, $sy, 75);

    }

    private function resize(string $filename,$width = 0, $height = 0  ): void
    {
        $imagine = new Imagine();
        list($iwidth, $iheight) = getimagesize($filename);
        $ratio = $iwidth / $iheight;
        if ($width == 0) $width = $this->largeur;
        if ($height == 0) $height = $this->hauteur;
        if ($width / $height > $ratio) {
            $width = $height * $ratio;
        } else {
            $height = $width / $ratio;
        }

        $photo = $imagine->open($filename);
        $photo->resize(new Box($width, $height))->save($filename);
    }
}
