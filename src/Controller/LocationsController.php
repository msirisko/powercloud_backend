<?php

namespace App\Controller;

use App\Entity\Locations;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Service\JsonSerializer;

class LocationsController extends AbstractController
{

 #[Route('/locations/all', name: 'show_locations_all', methods: ['GET', 'HEAD'])]
    public function showLocations(ManagerRegistry $doctrine, JsonSerializer $JsonSerializer): Response
{
    $data = $doctrine->getRepository(Locations::class)->findAll();
    $jsonContent = $JsonSerializer->serializeJson($data);
    return new Response($jsonContent);
}

    #[Route('/locations/add', name: 'add_location', methods: ['POST', 'HEAD'])]
    public function addLocation(ManagerRegistry $doctrine, Request $request, JsonSerializer $JsonSerializer): Response
    {
        $entityManager = $doctrine->getManager();
        $data = $request->getContent();
        $location = $JsonSerializer->deserializeJson($data, Locations::class);
        $entityManager->persist($location);
        $entityManager->flush();
        return new Response($data);
    }
    #[Route('/locations/{continent}', name: 'get_location_byContinent', methods: ['GET', 'HEAD'])]

   /* public function showLocationByContinent(Request $request)
    {
        $q = $request->query->get('continent');
        // Get standard repository
        $user = $this->getDoctrine()->getManager()
            ->getRepository(Locations::class)
            ->findBy(['continent' => $q]); // Or use the magic method ->findByEmail($q);

        // Present your results
        return $this->render('admin/search/search_results.html.twig',
            ['location' => $user]);
    } */
    public function findByContinent(string $continent, ManagerRegistry $doctrine): ?Locations{
       $entityManager = $doctrine->getManager();
        return $entityManager->createQuery(
            'SELECT c
             FROM App\Entity\Locations c
             WHERE c.continent LIKE :continent'

        )
            ->setParameters([
                ':continent' => '%'.$continent.'%',
            ])
            ->getSingleResult()
            ;

        }

}
