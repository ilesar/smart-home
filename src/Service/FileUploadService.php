<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Image;
use Exception;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploadService
{
    /**
     * @var string
     */
    private $uploadPath;

    public function __construct(string $uploadPath)
    {
        $this->uploadPath = $uploadPath;
    }

    public function uploadFile(Image $image): void
    {
        $hashedFilename = $this->saveFileToDisk($image);
        $image->setFilename($hashedFilename);
    }

    public function saveFileToDisk(Image $image): string
    {
        $uploadedFile = $this->getUploadedFile($image);
        $hashedFileName = $this->createHashedFilename($uploadedFile);
        $uploadedFile->move($this->uploadPath, $hashedFileName);

        return $hashedFileName;
    }

    private function createHashedFilename(UploadedFile $uploadedFile)
    {
        return vsprintf('%s.%s', [
            sha1(random_bytes(20)),
            $uploadedFile->guessExtension(),
        ]);
    }

    private function getUploadedFile(Image $image)
    {
        $uploadedFile = $image->getUploadedFile();

        if (null === $uploadedFile) {
            throw new Exception('Uploaded file missing');
        }

        return $uploadedFile;
    }
}
