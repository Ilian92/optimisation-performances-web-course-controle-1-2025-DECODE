<?php

namespace App\Controller;

use App\Repository\GalaxyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

final class CarouselController extends AbstractController
{
    #[Route('/carousel', name: 'app_carousel')]
    #[Cache(smaxage: 3600, maxage: 3600)]
    public function index(GalaxyRepository $galaxyRepository, CacheInterface $cache): Response
    {
        $carousel = $cache->get('carousel_data_v1', function (ItemInterface $item) use ($galaxyRepository) {
            $item->expiresAfter(3600);

            $galaxies = $galaxyRepository->findAllWithRelations();
            $data = [];

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
                $data[] = $carouselItem;
            }

            return $data;
        });

        return $this->render('carousel/index.html.twig', [
            'carousel' => $carousel
        ]);
    }
}
