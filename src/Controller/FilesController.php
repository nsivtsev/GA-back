<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Routing\Annotation\Route;

class FilesController extends AbstractController
{
    /**
     * @Route("/{class}/{filename}", name="app_photo")
     *   requirements = {
     *     "class" = "photo|cv"
     *   },
     * @param string $class
     * @param string $filename
     * @param UserRepository $userRepository
     * @return BinaryFileResponse
     */
    public function index(string $class, string $filename, UserRepository $userRepository)
    {
            return new BinaryFileResponse('..'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.$class.DIRECTORY_SEPARATOR.$filename);

    }
}
