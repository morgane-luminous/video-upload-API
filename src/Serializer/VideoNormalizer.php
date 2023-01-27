<?php

namespace App\Serializer;

use App\Entity\Video;
use Doctrine\DBAL\Exception;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Vich\UploaderBundle\Storage\StorageInterface;

class VideoNormalizer implements NormalizerInterface
{
    private const ALREADY_CALLED = 'VIDEO_NORMALIZER_ALREADY_CALLED';

    public function __construct(
        private StorageInterface $storage,
        private ObjectNormalizer $normalizer
    )
    {
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }

        return $data instanceof Video;
    }

    /**
     * @param Video $object
     * @param string|null $format
     * @param array $context
     * @return array|string|int|float|bool|\ArrayObject|null
     * @throws ExceptionInterface|Exception
     */
    public function normalize($object, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $context[self::ALREADY_CALLED] = true;

        if (null === $object->getUri()) {
            if (null === $object->getFile()) {
                throw new Exception('Invalid entity: has neither video file nor url.');
            }
            $fileUri = $this->storage->resolveUri($object, 'file');
            $object->setUri($fileUri);
        }

        return $this->normalizer->normalize($object, $format, $context);
    }
}