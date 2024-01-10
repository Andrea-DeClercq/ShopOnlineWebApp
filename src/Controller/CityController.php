<?php

namespace App\Controller;

use App\Form\CityCreateType;
use App\Form\CityEditType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/cities', name:'app_city')]
class CityController extends AbstractController
{
    public function __construct(
        private HttpClientInterface $client,
    ){
    }

    #[Route('/actions', name: '_actions')]
    public function cityActions(Request $request)
    {
        $cityId = $request->request->get('cityId');
        $action = $request->request->get('action');

        // Traiter les actions en fonction du bouton appuyé
        switch ($action) {
            case 'view':
                return $this->redirectToRoute('app_city_id', ['id' => $cityId]);
            case 'edit':
                return $this->redirectToRoute('app_city_edit', ['id' => $cityId]);
            case 'delete':
                return $this->redirectToRoute('app_city_delete', ['id' => $cityId]);
            default:
                return new JsonResponse(['status' => 'Error', 'message' => 'Action non valide']);
        }
    }

    #[Route('/', name:'_index')]
    public function index(): Response
    {
        return $this->render('city/index.html.twig', [
        ]);
    }

    #[Route('/get-all', name: '_all')]
    public function getAllCity(): JsonResponse
    {
        $response = $this->client->request(
            'GET',
            'http://localhost:8080/RestGlassfishHelloWorld-1.0-SNAPSHOT/api/cities/'
        );

        $statusCode = $response->getStatusCode();
        $contentType = $response->getHeaders()['content-type'][0];
        $content = $response->getContent();
        $content = $response->toArray();
        return new JsonResponse($content, $statusCode);
    }

    #[Route('/get/{id}', name: '_id')]
    public function getCityId(int $id): JsonResponse
    {
        $response = $this->client->request(
            'GET',
            'http://localhost:8080/RestGlassfishHelloWorld-1.0-SNAPSHOT/api/cities/GET/'.$id
        );

        $statusCode = $response->getStatusCode();
        $contentType = $response->getHeaders()['content-type'][0];
        $content = $response->getContent();
        $content = $response->toArray();
        return new JsonResponse($content, $statusCode);
    }

    #[Route('/delete/{id}', name: '_delete')]
    public function deleteCityById(int $id)
    {
        $response = $this->client->request(
            'DELETE',
            'http://localhost:8080/RestGlassfishHelloWorld-1.0-SNAPSHOT/api/cities/delete/'.$id
        );

        $statusCode = $response->getStatusCode();

        $responseData = [
            'status' => $statusCode == 200 ? 'Success' : 'Error',
            'content' => $statusCode == 200 ? 'Delete City ' .$id : 'Error status code '.$statusCode,
        ];

        return new JsonResponse($responseData, $statusCode);
    }

    #[Route('/edit/{id}', name: '_edit')]
    public function editCity(string $id, Request $request)
    {
        $response = $this->client->request(
            'GET',
            'http://localhost:8080/RestGlassfishHelloWorld-1.0-SNAPSHOT/api/cities/GET/'.$id
        );

        $statusCode = $response->getStatusCode();
        $content = $response->toArray();

        $form = $this->createForm(CityEditType::class, $content);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $apiData = [
                'id' => $id,
                'name' => $formData['name'],
                'countryCode' => $formData['countryCode'],
                'district' => $formData['district'],
            ];

            $apiResponse = $this->client->request(
                'PUT',
                'http://localhost:8080/RestGlassfishHelloWorld-1.0-SNAPSHOT/api/cities',
                [
                    'headers' => ['Content-Type' => 'application/json'],
                    'json' => $apiData,
                ]
            );

            $apiStatusCode = $apiResponse->getStatusCode();

            if ($apiStatusCode == 200) {
                return new JsonResponse(['status' => 'Success', 'message' => 'City updated successfully'], 200);
            } else {
                return new JsonResponse(['status' => 'Error', 'message' => 'Failed to update city'], $apiStatusCode);
            }
        }

        return $this->render('city/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/create', name: '_create')]
    public function createCity(Request $request)
    {
        $form = $this->createForm(CityCreateType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $apiData = [
                'name' => $formData['name'],
                'countryCode' => $formData['countryCode'],
                'district' => $formData['district'],
            ];

            $apiResponse = $this->client->request(
                'POST',
                'http://localhost:8080/RestGlassfishHelloWorld-1.0-SNAPSHOT/api/cities',
                [
                    'headers' => ['Content-Type' => 'application/json'],
                    'json' => $apiData,
                ]
            );

            $apiStatusCode = $apiResponse->getStatusCode();

            if ($apiStatusCode == 201) {
                return new JsonResponse(['status' => 'Success', 'message' => 'City created successfully'], 201);
            } else {
                return new JsonResponse(['status' => 'Error', 'message' => 'Failed to create city'], $apiStatusCode);
            }
        }

        return $this->render('city/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
