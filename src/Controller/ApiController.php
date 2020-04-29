<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Log\Logger;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ApiController extends AbstractController
{
    /**
     * @Route("/api", name="api")
     */
    public function index()
    {
        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }

    /**
     * @Route("/api/fetch_user", name="api_fetch_user")
     */
    public function fetchUser()
    {
        $user = $this->getUser();
        if ($user)
            $response = $user->getInfo();
        else
            throw new UnauthorizedHttpException('Ошибка авторизации');

        return $this->json($response);
    }

    /**
     * @Route("/api/update_user", name="api_update_user")
     * @param Request $request
     * @return JsonResponse
     */
    public function updateUser(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();
        if ($user) {
            $userInfo = json_decode($request->getContent(), true);

            if (array_key_exists('name', $userInfo))
                $user->setName($userInfo['name']);
            if (array_key_exists('surname', $userInfo))
                $user->setSurname($userInfo['surname']);
            if (array_key_exists('patronymic', $userInfo))
                $user->setPatronymic($userInfo['patronymic']);
            if (array_key_exists('email', $userInfo))
                $user->setEmail($userInfo['email']);
            if (array_key_exists('tel', $userInfo))
                $user->setTelephone($userInfo['tel']);

            $this->getDoctrine()->getManager()->flush();
        } else
            throw new UnauthorizedHttpException('Ошибка авторизации');


        return $this->json($user->getInfo());
    }

    /**
     * @Route("/api/upload/{class}",
     *     name="api_upload",
     *     requirements = {
     *        "class" = "photo|cv"
     *   })
     * @param string $class
     * @param Request $request
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function upload(string $class, Request $request, SerializerInterface $serializer)
    {
        if ($this->getUser()) {
            /** @var UploadedFile $photo */
            $photo = $request->files->get('file');
            $dirName = '..' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $class . DIRECTORY_SEPARATOR;
            if ($photo) {
                $safeFilename = uniqid();
                $newFilename = $safeFilename . '.' . $photo->guessExtension();

                try {
                    $photo->move(
                        $dirName,
                        $newFilename
                    );
                } catch (FileException $e) {
                    return $this->json(['status' => 'error', 'message' => $e->getMessage()]);
                }

            }
            if ($class == 'photo')
                $this->getUser()->setPhoto($newFilename);
            if ($class == 'cv')
                $this->getUser()->setCv($newFilename);
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->json(['status' => 'OK']);
    }

}