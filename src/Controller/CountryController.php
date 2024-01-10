<?php

namespace App\Controller;

use App\Form\CountryCreateType;
use App\Form\CountryEditType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/country', name: 'app_country')]
class CountryController extends AbstractController
{
    public function __construct(
        private HttpClientInterface $client,
    ){
    }

    #[Route('/actions', name: '_actions')]
    public function countryActions(Request $request)
    {
        $countryId = $request->request->get('countryId');
        $action = $request->request->get('action');

        // Traiter les actions en fonction du bouton appuyé
        switch ($action) {
            case 'view':
                return $this->redirectToRoute('app_country_id', ['id' => $countryId]);
            case 'edit':
                return $this->redirectToRoute('app_country_edit', ['id' => $countryId]);
            case 'delete':
                return $this->redirectToRoute('app_country_delete', ['id' => $countryId]);
            default:
                return new JsonResponse(['status' => 'Error', 'message' => 'Action non valide']);
        }
    }

    #[Route('/', name:'_index')]
    public function index(): Response
    {
        return $this->render('country/index.html.twig', [
        ]);
    }

    #[Route('/get-all', name: '_all')]
    public function getAllCountry(): JsonResponse
    {
        $response = $this->client->request(
            'GET',
            'http://localhost:8080/RestGlassfishHelloWorld-1.0-SNAPSHOT/api/countries/'
        );

        $statusCode = $response->getStatusCode();
        $contentType = $response->getHeaders()['content-type'][0];
        $content = $response->getContent();
        $content = $response->toArray();
        return new JsonResponse($content, $statusCode);
    }

    #[Route('/get/{id}', name: '_id')]
    public function getContryById(string $id): JsonResponse
    {
        $response = $this->client->request(
            'GET',
            'http://localhost:8080/RestGlassfishHelloWorld-1.0-SNAPSHOT/api/countries/GET/'.$id
        );

        $statusCode = $response->getStatusCode();
        $contentType = $response->getHeaders()['content-type'][0];
        $content = $response->getContent();
        $content = $response->toArray();
        return new JsonResponse($content, $statusCode);
    }

    #[Route('/delete/{id}', name: '_delete')]
    public function deleteCountryById(string $id)
    {
        $response = $this->client->request(
            'DELETE',
            'http://localhost:8080/RestGlassfishHelloWorld-1.0-SNAPSHOT/api/countries/delete/'.$id
        );

        $statusCode = $response->getStatusCode();

        $responseData = [
            'status' => $statusCode == 200 ? 'Success' : 'Error',
            'content' => $statusCode == 200 ? 'Delete Countries ' .$id : 'Error status code '.$statusCode,
        ];

        return new JsonResponse($responseData, $statusCode);
    }

    #[Route('/edit/{id}', name: '_edit')]
    public function editCountry(string $id, Request $request)
    {
        $response = $this->client->request(
            'GET',
            'http://localhost:8080/RestGlassfishHelloWorld-1.0-SNAPSHOT/api/countries/GET/'.$id
        );

        $statusCode = $response->getStatusCode();
        $content = $response->toArray();

        $form = $this->createForm(CountryEditType::class, $content);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $apiData = [
                'id' => $id,
                'code' => $formData['code'],
                'name' => $formData['name'],
                'continent' => $formData['continent'],
                'region' => $formData['region'],
                'localName' => $formData['localName'],
                'capital' => $formData['capital'],
                'code2' => $formData['code2'],
            ];

            $apiResponse = $this->client->request(
                'PUT',
                'http://localhost:8080/RestGlassfishHelloWorld-1.0-SNAPSHOT/api/countries',
                [
                    'headers' => ['Content-Type' => 'application/json'],
                    'json' => $apiData,
                ]
            );

            $apiStatusCode = $apiResponse->getStatusCode();

            if ($apiStatusCode == 200) {
                return new JsonResponse(['status' => 'Success', 'message' => 'Country updated successfully'], 200);
            } else {
                return new JsonResponse(['status' => 'Error', 'message' => 'Failed to update country'], $apiStatusCode);
            }
        }

        return $this->render('country/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/create', name: '_create')]
    public function createCountry(Request $request)
    {
        $form = $this->createForm(CountryCreateType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $apiData = [
                'code' => $formData['code'],
                'name' => $formData['name'],
                'continent' => $formData['continent'],
                'region' => $formData['region'],
                'localName' => $formData['localName'],
                'capital' => $formData['capital'],
                'code2' => $formData['code2'],
            ];

            $apiResponse = $this->client->request(
                'POST',
                'http://localhost:8080/RestGlassfishHelloWorld-1.0-SNAPSHOT/api/countries',
                [
                    'headers' => ['Content-Type' => 'application/json'],
                    'json' => $apiData,
                ]
            );

            $apiStatusCode = $apiResponse->getStatusCode();

            if ($apiStatusCode == 201) {
                return new JsonResponse(['status' => 'Success', 'message' => 'User created successfully'], 201);
            } else {
                return new JsonResponse(['status' => 'Error', 'message' => 'Failed to create user'], $apiStatusCode);
            }
        }

        return $this->render('country/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
