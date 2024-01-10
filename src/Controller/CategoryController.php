<?php

namespace App\Controller;

use App\Form\CategoryCreateType;
use App\Form\CategoryEditType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/category', name: 'app_category')]
class CategoryController extends AbstractController
{
    public function __construct(
        private HttpClientInterface $client,
    ){
    }

    #[Route('/actions', name: '_actions')]
    public function categoryActions(Request $request)
    {
        $categoryId = $request->request->get('categoryId');
        $action = $request->request->get('action');

        // Traiter les actions en fonction du bouton appuyé
        switch ($action) {
            case 'view':
                return $this->redirectToRoute('app_category_id', ['id' => $categoryId]);
            case 'edit':
                return $this->redirectToRoute('app_category_edit', ['id' => $categoryId]);
            case 'delete':
                return $this->redirectToRoute('app_category_delete', ['id' => $categoryId]);
            default:
                return new JsonResponse(['status' => 'Error', 'message' => 'Action non valide']);
        }
    }
    #[Route('/', name: '_index')]
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
        ]);
    }

    #[Route('/get-all', name: '_all')]
    public function getAllCategory(): JsonResponse
    {
        $response = $this->client->request(
            'GET',
            'http://localhost:8080/RestGlassfishHelloWorld-1.0-SNAPSHOT/api/categories/'
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
            'http://localhost:8080/RestGlassfishHelloWorld-1.0-SNAPSHOT/api/categories/GET/'.$id
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
            'http://localhost:8080/RestGlassfishHelloWorld-1.0-SNAPSHOT/api/categories/delete/'.$id
        );

        $statusCode = $response->getStatusCode();

        $responseData = [
            'status' => $statusCode == 200 ? 'Success' : 'Error',
            'content' => $statusCode == 200 ? 'Delete Category ' .$id : 'Error status code '.$statusCode,
        ];

        return new JsonResponse($responseData, $statusCode);
    }

    #[Route('/edit/{id}', name: '_edit')]
    public function editCategory(string $id, Request $request)
    {
        $response = $this->client->request(
            'GET',
            'http://localhost:8080/RestGlassfishHelloWorld-1.0-SNAPSHOT/api/categories/GET/'.$id
        );

        $statusCode = $response->getStatusCode();
        $content = $response->toArray();

        $form = $this->createForm(CategoryEditType::class, $content);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $apiData = [
                'id' => $id,
                'title' => $formData['title'],
                'webTitle' => $formData['webTitle'],
                'parent' => $formData['parent'],
                'level' => $formData['level']
            ];

            $apiResponse = $this->client->request(
                'PUT',
                'http://localhost:8080/RestGlassfishHelloWorld-1.0-SNAPSHOT/api/categories',
                [
                    'headers' => ['Content-Type' => 'application/json'],
                    'json' => $apiData,
                ]
            );

            $apiStatusCode = $apiResponse->getStatusCode();

            if ($apiStatusCode == 200) {
                return new JsonResponse(['status' => 'Success', 'message' => 'Category updated successfully'], 200);
            } else {
                return new JsonResponse(['status' => 'Error', 'message' => 'Failed to update category'], $apiStatusCode);
            }
        }

        return $this->render('category/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/create', name: '_create')]
    public function createCategory(Request $request)
    {
        $form = $this->createForm(CategoryCreateType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $apiData = [
                'title' => $formData['title'],
                'webTitle' => $formData['webTitle'],
                'parent' => $formData['parent'],
                'level' => $formData['level']
            ];

            $apiResponse = $this->client->request(
                'POST',
                'http://localhost:8080/RestGlassfishHelloWorld-1.0-SNAPSHOT/api/categories',
                [
                    'headers' => ['Content-Type' => 'application/json'],
                    'json' => $apiData,
                ]
            );

            $apiStatusCode = $apiResponse->getStatusCode();

            if ($apiStatusCode == 201) {
                return new JsonResponse(['status' => 'Success', 'message' => 'Category created successfully'], 201);
            } else {
                return new JsonResponse(['status' => 'Error', 'message' => 'Failed to create category'], $apiStatusCode);
            }
        }

        return $this->render('category/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
