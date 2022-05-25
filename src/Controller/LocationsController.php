<?php

namespace App\Controller;

use App\Entity\Locations;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Service\JsonSerializer;

class LocationsController extends AbstractController
{

 #[Route('/locations/', name: 'get_locations_all', methods: ['GET', 'HEAD'])]
    public function showLocations(ManagerRegistry $doctrine, JsonSerializer $JsonSerializer): Response
{
    $data = $doctrine->getRepository(Locations::class)->findAll();
    $jsonContent = $JsonSerializer->serializeJson($data);
    return new Response($jsonContent);
}
}
