<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\Image;
use App\Service\FileUploadService;

class FileUploadListener
{
    /**
     * @var FileUploadService
     */
    private $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    public function prePersist(Image $image): void
    {
        $this->fileUploadService->uploadFile($image);
    }
}
