<?php

namespace App\Controller;

use ApiPlatform\Api\IriConverterInterface;
use App\Entity\Video;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

#[AsController]
class CreateVideoAction extends AbstractController
{
    public function __construct(private IriConverterInterface $iriConverter)
    {
    }

    public function __invoke(Request $request): Video
    {
        $uploadedFile = $request->files->get('file');

        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        $title = $request->request->get('title');
        $description = $request->request->get('description');
        $categories = $request->request->all()['categories'] ?? null; // https://github.com/symfony/symfony/issues/44432

        $video = new Video();
        $video->setFile($uploadedFile)
            ->setTitle($title)
            ->setDescription($description)
        ;

        if (is_array($categories)) {
            $categories = array_map(function (string $category) {
                return $this->iriConverter->getResourceFromIri($category);
            }, $categories);

            $video->setCategories($categories);
        }

        return $video;
    }
}
