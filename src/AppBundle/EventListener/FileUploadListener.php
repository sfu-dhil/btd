<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\MediaFile;
use AppBundle\Services\FileUploader;
use AppBundle\Services\Thumbnailer;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploadListener {
    /**
     * @var FileUploader
     */
    private $uploader;

    /**
     * @var Thumbnailer
     */
    private $thumbnailer;

    public function __construct(FileUploader $uploader, Thumbnailer $thumbnailer) {
        $this->uploader = $uploader;
        $this->thumbnailer = $thumbnailer;
    }

    private function uploadFile($entity) {
        if ( ! $entity instanceof MediaFile) {
            return;
        }
        $uploadedFile = $entity->getFile();
        if ( ! $uploadedFile instanceof UploadedFile) {
            return;
        }
        $filename = $this->uploader->upload($uploadedFile);
        $entity->setFilename($filename);
        $entity->setFile(new File($this->uploader->getUploadDir() . '/' . $filename));
        $this->thumbnailer->generateThumbnail($entity);
    }

    public function prePersist(LifecycleEventArgs $args) {
        $this->uploadFile($args->getEntity());
    }

    public function preUpdate(PreUpdateEventArgs $args) {
        $this->uploadFile($args->getEntity());
    }

    public function postLoad(LifecycleEventArgs $args) {
        $entity = $args->getEntity();
        if ( ! $entity instanceof MediaFile) {
            return;
        }
        $filename = $entity->getFilename();
        $path = $this->uploader->getUploadDir() . '/' . $filename;
        if (file_exists($path)) {
            $entity->setFile(new File($path));
        } else {
            $entity->setFile(new File($this->uploader->getUploadDir() . '/../misc/missing-file.jpg'));
        }
    }
}
