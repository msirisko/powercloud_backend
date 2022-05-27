<?php

namespace App\Controller;

use App\Entity\Locations;
use App\Entity\News;
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
    // existing URLS: '/locations/Europa', '/locations/Nord-Amerika', 'locations/Asien-Pazifik
    #[Route('/locations/{continent}', name: 'show_locations_byContinent', methods: ['GET', 'HEAD'])]
    public function showLocationsByContinent($continent, ManagerRegistry $doctrine, JsonSerializer $JsonSerializer): Response
    {
        $data = $doctrine->getRepository(Locations::class)->findBy(['continent' => $continent]);
        $jsonContent = $JsonSerializer->serializeJson($data);
        return new Response($jsonContent);
    }
}
