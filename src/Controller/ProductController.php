<?php

namespace App\Controller;

use App\Form\ProductCreateType;
use App\Form\ProductEditType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/product')]
class ProductController extends AbstractController
{
    public function __construct(
        private HttpClientInterface $client,
    ){
    }
    #[Route('/actions', name: 'app_product_actions')]
    public function productActions(Request $request)
    {
        $productId = $request->request->get('productId');
        $action = $request->request->get('action');

        // Traiter les actions en fonction du bouton appuyé
        switch ($action) {
            case 'view':
                return $this->redirectToRoute('app_product_id', ['id' => $productId]);
            case 'edit':
                return $this->redirectToRoute('app_product_edit', ['id' => $productId]);
            case 'delete':
                return $this->redirectToRoute('app_product_delete', ['id' => $productId]);
            default:
                return new JsonResponse(['status' => 'Error', 'message' => 'Action non valide']);
        }
    }
    #[Route('/', name: 'app_product_index')]
    public function indexProduct(): Response
    {
        return $this->render('product/index.html.twig', [
        ]);
    }

    #[Route('/get-all', name: 'app_product_all')]
    public function getAllProduct(): JsonResponse
    {
        $response = $this->client->request(
            'GET',
            'http://localhost:8080/RestGlassfishHelloWorld-1.0-SNAPSHOT/api/products'
        );

        $statusCode = $response->getStatusCode();
        $contentType = $response->getHeaders()['content-type'][0];
        $content = $response->getContent();
        $content = $response->toArray();
        return new JsonResponse($content, $statusCode);
    }

    #[Route('/get/{id}', name: 'app_product_id')]
    public function getProductById(int $id): JsonResponse
    {
        $response = $this->client->request(
            'GET',
            'http://localhost:8080/RestGlassfishHelloWorld-1.0-SNAPSHOT/api/products/GET/'.$id
        );

        $statusCode = $response->getStatusCode();
        $contentType = $response->getHeaders()['content-type'][0];
        $content = $response->getContent();
        $content = $response->toArray();
        return new JsonResponse($content, $statusCode);
    }

    #[Route('/delete/{id}', name: 'app_product_delete')]
    public function deleteProductById(int $id)
    {
        $response = $this->client->request(
            'DELETE',
            'http://localhost:8080/RestGlassfishHelloWorld-1.0-SNAPSHOT/api/products/delete/'.$id
        );

        $statusCode = $response->getStatusCode();

        $responseData = [
            'status' => $statusCode == 200 ? 'Success' : 'Error',
            'content' => $statusCode == 200 ? 'Delete Product ' .$id : 'Error status code '.$statusCode,
        ];

        return new JsonResponse($responseData, $statusCode);
    }

    #[Route('/edit/{id}', name: 'app_product_edit')]
    public function editProduct(int $id, Request $request)
    {
        $response = $this->client->request(
            'GET',
            'http://localhost:8080/RestGlassfishHelloWorld-1.0-SNAPSHOT/api/products/GET/'.$id
        );
    
        $statusCode = $response->getStatusCode();
        $content = $response->toArray();
    
        $form = $this->createForm(ProductEditType::class, $content);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
    
            $apiData = [
                'productId' => $id,
                'productName' => $formData['productName'],
                'productDescription' => $formData['productDescription'],
                'dossier' => $formData['dossier'],
                'categoryId' => $formData['categoryId'],
                'inStock' => $formData['inStock'],
                'price' => $formData['price'],
                'brand' => $formData['brand'],
                'nbrImage' => $formData['nbrImage'],
                'dateAdded' => (new \DateTime('now'))->format('Y-m-d\TH:i:s\Z'),
            ];

            $apiResponse = $this->client->request(
                'PUT',
                'http://localhost:8080/RestGlassfishHelloWorld-1.0-SNAPSHOT/api/products',
                [
                    'headers' => ['Content-Type' => 'application/json'],
                    'json' => $apiData,
                ]
            );
    
            $apiStatusCode = $apiResponse->getStatusCode();
    
            if ($apiStatusCode == 200) {
                return new JsonResponse(['status' => 'Success', 'message' => 'Product updated successfully'], 200);
            } else {
                return new JsonResponse(['status' => 'Error', 'message' => 'Failed to update product'], $apiStatusCode);
            }
        }

        return $this->render('product/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/create', name: 'app_product_create')]
    public function createProduct(Request $request)
    {
        $form = $this->createForm(ProductCreateType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $apiData = [
                'productName' => $formData['productName'],
                'productDescription' => $formData['productDescription'],
                'dossier' => $formData['dossier'],
                'categoryId' => $formData['categoryId'],
                'inStock' => $formData['inStock'],
                'price' => $formData['price'],
                'brand' => $formData['brand'],
                'nbrImage' => $formData['nbrImage'],
                'dateAdded' => (new \DateTime('now'))->format('Y-m-d\TH:i:s\Z'),
            ];

            $apiResponse = $this->client->request(
                'POST',
                'http://localhost:8080/RestGlassfishHelloWorld-1.0-SNAPSHOT/api/products',
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

        return $this->render('product/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
