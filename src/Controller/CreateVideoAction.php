<?php

namespace App\Controller;

use App\Entity\Video;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Serializer\SerializerInterface;

#[AsController]
class CreateVideoAction extends AbstractController
{
    public function __construct(private SerializerInterface $serializer)
    {
    }

    public function __invoke(Request $request): Video
    {
        $jsonRequest  = json_encode($request->request->all());
        $video        = $this->serializer->deserialize($jsonRequest, Video::class, 'json');
        $uploadedFile = $request->files->get('file');

        $video->setFile($uploadedFile);

        return $video;
    }
}
