<?php

namespace App\Controller;

use App\Service\ResourceGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ResourceController extends AbstractController
{
    private $resourceGenerator = null;

    public function __construct(ResourceGenerator $resourceGenerator)
    {
        $this->resourceGenerator = $resourceGenerator;
    }

    #[Route('/resource/{id}', name: 'api_resource')]
    public function index($id): JsonResponse
    {
        $result = $this->resourceGenerator->getResource($id);

        return $this->json([
            'resource' => $result,
        ]);
    }
}
