<?php

namespace App\Controller;

use App\Repository\GalaxyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CarouselController extends AbstractController
{
    #[Route('/carousel', name: 'app_carousel')]
    public function index(GalaxyRepository $galaxyRepository): Response
    {
        $galaxies = $galaxyRepository->findAllWithRelations();

        $carousel = [];

        foreach ($galaxies as $galaxy) {
            $carouselItem = [
                'title' => $galaxy->getTitle(),
                'description' => $galaxy->getDescription(),
            ];

            $modele = $galaxy->getModele();
            $files = [];

            if ($modele) {
                foreach ($modele->getModelesFiles() as $modelesFile) {
                    if ($file = $modelesFile->getDirectusFiles()) {
                        $files[] = $file;
                    }
                }
            }
            $carouselItem['files'] = $files;
            $carousel[] = $carouselItem;
        }

        return $this->render('carousel/index.html.twig', [
            'carousel' => $carousel
        ]);
    }
}
