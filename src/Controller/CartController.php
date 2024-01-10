<?php

namespace App\Controller;

use App\Form\CartCreateType;
use App\Form\CartEditType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/carts', name: 'app_carts')]
class CartController extends AbstractController
{

    public function __construct(
        private HttpClientInterface $client,
    ){
    }
    
    #[Route('/actions', name: '_actions')]
    public function categoryActions(Request $request)
    {
        $cartId = $request->request->get('cartId');
        $action = $request->request->get('action');

        // Traiter les actions en fonction du bouton appuyé
        switch ($action) {
            case 'view':
                return $this->redirectToRoute('app_carts_id', ['id' => $cartId]);
            case 'edit':
                return $this->redirectToRoute('app_carts_edit', ['id' => $cartId]);
            case 'delete':
                return $this->redirectToRoute('app_carts_delete', ['id' => $cartId]);
            default:
                return new JsonResponse(['status' => 'Error', 'message' => 'Action non valide']);
        }
    }
    #[Route('/', name: '_index')]
    public function index(): Response
    {
        return $this->render('cart/index.html.twig', [
        ]);
    }

    #[Route('/get-all', name: '_all')]
    public function getAllCategory(): JsonResponse
    {
        $response = $this->client->request(
            'GET',
            'http://localhost:8080/RestGlassfishHelloWorld-1.0-SNAPSHOT/api/carts/'
        );

        $statusCode = $response->getStatusCode();
        $contentType = $response->getHeaders()['content-type'][0];
        $content = $response->getContent();
        $content = $response->toArray();
        return new JsonResponse($content, $statusCode);
    }

    #[Route('/get/{id}', name: '_id')]
    public function getCategoryId(int $id): JsonResponse
    {
        $response = $this->client->request(
            'GET',
            'http://localhost:8080/RestGlassfishHelloWorld-1.0-SNAPSHOT/api/carts/GET/'.$id
        );

        $statusCode = $response->getStatusCode();
        $contentType = $response->getHeaders()['content-type'][0];
        $content = $response->getContent();
        $content = $response->toArray();
        return new JsonResponse($content, $statusCode);
    }

    #[Route('/delete/{id}', name: '_delete')]
    public function deleteCategoryById(int $id)
    {
        $response = $this->client->request(
            'DELETE',
            'http://localhost:8080/RestGlassfishHelloWorld-1.0-SNAPSHOT/api/carts/delete/'.$id
        );

        $statusCode = $response->getStatusCode();

        $responseData = [
            'status' => $statusCode == 200 ? 'Success' : 'Error',
            'content' => $statusCode == 200 ? 'Delete Cart ' .$id : 'Error status code '.$statusCode,
        ];

        return new JsonResponse($responseData, $statusCode);
    }

    #[Route('/edit/{id}', name: '_edit')]
    public function editCategory(string $id, Request $request)
    {
        $response = $this->client->request(
            'GET',
            'http://localhost:8080/RestGlassfishHelloWorld-1.0-SNAPSHOT/api/carts/GET/'.$id
        );

        $statusCode = $response->getStatusCode();
        $content = $response->toArray();

        $form = $this->createForm(CartEditType::class, $content);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $apiData = [
                'idCart' => $id,
                'idUser' => $formData['idUser'],
                'idProduct' => $formData['idProduct'],
                'quantity' => $formData['quantity'],
                'payed' => $formData['payed'],
                'confirmed' => $formData['confirmed'],
            ];

            $apiResponse = $this->client->request(
                'PUT',
                'http://localhost:8080/RestGlassfishHelloWorld-1.0-SNAPSHOT/api/carts',
                [
                    'headers' => ['Content-Type' => 'application/json'],
                    'json' => $apiData,
                ]
            );

            $apiStatusCode = $apiResponse->getStatusCode();

            if ($apiStatusCode == 200) {
                return new JsonResponse(['status' => 'Success', 'message' => 'Cart updated successfully'], 200);
            } else {
                return new JsonResponse(['status' => 'Error', 'message' => 'Failed to update cart'], $apiStatusCode);
            }
        }

        return $this->render('cart/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/create', name: '_create')]
    public function createCategory(Request $request)
    {
        $form = $this->createForm(CartCreateType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $apiData = [
                'idUser' => $formData['idUser'],
                'idProduct' => $formData['idProduct'],
                'quantity' => $formData['quantity'],
                'payed' => $formData['payed'],
                'confirmed' => $formData['confirmed'],
            ];

            $apiResponse = $this->client->request(
                'POST',
                'http://localhost:8080/RestGlassfishHelloWorld-1.0-SNAPSHOT/api/carts',
                [
                    'headers' => ['Content-Type' => 'application/json'],
                    'json' => $apiData,
                ]
            );

            $apiStatusCode = $apiResponse->getStatusCode();

            if ($apiStatusCode == 201) {
                return new JsonResponse(['status' => 'Success', 'message' => 'Cart created successfully'], 201);
            } else {
                return new JsonResponse(['status' => 'Error', 'message' => 'Failed to create cart'], $apiStatusCode);
            }
        }

        return $this->render('cart/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
