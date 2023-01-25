<?php

namespace App\Controller;

use App\Entity\Video;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
class CreateVideoAction extends AbstractController
{
    public function __invoke(Request $request): Video
    {
        $uploadedFile = $request->files->get('file');

        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        $title = $request->request->get('title');
        $description = $request->request->get('description');

        $video = new Video();
        $video->setFile($uploadedFile)
            ->setTitle($title)
            ->setDescription($description)
        ;

        return $video;
    }
}
