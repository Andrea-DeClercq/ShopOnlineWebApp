<?php

namespace App\Controller;

use App\Form\UserCreateType;
use App\Form\UserEditType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/users')]
class UserController extends AbstractController
{
    public function __construct(
        private HttpClientInterface $client,
    ){
    }

    #[Route('/actions', name: 'app_user_actions')]
    public function userActions(Request $request)
    {
        $userId = $request->request->get('userId');
        $action = $request->request->get('action');

        // Traiter les actions en fonction du bouton appuyé
        switch ($action) {
            case 'view':
                return $this->redirectToRoute('app_user_id', ['id' => $userId]);
            case 'edit':
                return $this->redirectToRoute('app_user_edit', ['id' => $userId]);
            case 'delete':
                return $this->redirectToRoute('app_user_delete', ['id' => $userId]);
            default:
                return new JsonResponse(['status' => 'Error', 'message' => 'Action non valide']);
        }
    }
    #[Route('/', name: 'app_user_index')]
    public function indexUser(): Response
    {
        
        return $this->render('user/index.html.twig', [
        ]);
    }
    #[Route('/get-all', name: 'app_all_user')]
    public function getAllUser(): JsonResponse
    {
        $response = $this->client->request(
            'GET',
            'http://localhost:8080/RestGlassfishHelloWorld-1.0-SNAPSHOT/api/users'
        );

        $statusCode = $response->getStatusCode();
        $contentType = $response->getHeaders()['content-type'][0];
        $content = $response->getContent();
        $content = $response->toArray();
        return new JsonResponse($content, $statusCode);
    }

    #[Route('/get/{id}', name: 'app_user_id')]
    public function getUserById(int $id): JsonResponse
    {
        $response = $this->client->request(
            'GET',
            'http://localhost:8080/RestGlassfishHelloWorld-1.0-SNAPSHOT/api/users/GET/'.$id
        );

        $statusCode = $response->getStatusCode();
        $contentType = $response->getHeaders()['content-type'][0];
        $content = $response->getContent();
        $content = $response->toArray();
        return new JsonResponse($content, $statusCode);
    }

    #[Route('/delete/{id}', name: 'app_user_delete')]
    public function deleteUserById(int $id)
    {
        $response = $this->client->request(
            'DELETE',
            'http://localhost:8080/RestGlassfishHelloWorld-1.0-SNAPSHOT/api/users/delete/'.$id
        );

        $statusCode = $response->getStatusCode();

        $responseData = [
            'status' => $statusCode == 200 ? 'Success' : 'Error',
            'content' => $statusCode == 200 ? 'Delete userId ' .$id : 'Error status code '.$statusCode,
        ];

        return new JsonResponse($responseData, $statusCode);
    }

    #[Route('/edit/{id}', name: 'app_user_edit')]
    public function editUser(int $id, Request $request)
    {
        $response = $this->client->request(
            'GET',
            'http://localhost:8080/RestGlassfishHelloWorld-1.0-SNAPSHOT/api/users/GET/'.$id
        );

        $statusCode = $response->getStatusCode();
        $content = $response->toArray();

        $form = $this->createForm(UserEditType::class, $content);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $apiData = [
                'id' => $id,
                'userName' => $formData['userName'],
                'userEmail' => $formData['userEmail'],
                'userPhone' => $formData['userPhone'],
                'userFname' => $formData['userFname'],
                'userLname' => $formData['userLname'],
                'userPassword' => $formData['userPassword'],
                'userCityId' => $formData['userCityId'],
                'userAdress' => $formData['userAdress'],
                'userLoginStatus' => $formData['userLoginStatus'],
            ];

            $apiResponse = $this->client->request(
                'PUT',
                'http://localhost:8080/RestGlassfishHelloWorld-1.0-SNAPSHOT/api/users',
                [
                    'headers' => ['Content-Type' => 'application/json'],
                    'json' => $apiData,
                ]
            );

            $apiStatusCode = $apiResponse->getStatusCode();

            if ($apiStatusCode == 200) {
                return new JsonResponse(['status' => 'Success', 'message' => 'User updated successfully'], 200);
            } else {
                return new JsonResponse(['status' => 'Error', 'message' => 'Failed to update user'], $apiStatusCode);
            }
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/create', name: 'app_user_create')]
    public function createUser(Request $request)
    {
        $form = $this->createForm(UserCreateType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $apiData = [
                'userName' => $formData['userName'],
                'userEmail' => $formData['userEmail'],
                'userPhone' => $formData['userPhone'],
                'userFname' => $formData['userFname'],
                'userLname' => $formData['userLname'],
                'userPassword' => $formData['userPassword'],
                'userCityId' => $formData['userCityId'],
                'userAdress' => $formData['userAdress'],
                'userLoginStatus' => $formData['userLoginStatus'],
            ];

            $apiResponse = $this->client->request(
                'POST',
                'http://localhost:8080/RestGlassfishHelloWorld-1.0-SNAPSHOT/api/users',
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

        return $this->render('user/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
