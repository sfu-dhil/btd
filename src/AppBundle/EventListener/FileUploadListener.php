<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\MediaFile;
use AppBundle\Services\FileUploader;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploadListener {

    /**
     * @var FileUploader
     */
    private $uploader;

    public function __construct(FileUploader $uploader) {
        $this->uploader = $uploader;
    }

    public function prePersist(LifecycleEventArgs $args) {
        $this->upload($args->getEntity());
    }

    public function preUpdate(PreUpdateEventArgs $args) {
        if($args->getEntity() instanceof MediaFile) {
            $mediaFile = $args->getEntity();
            $filename = $mediaFile->getFilename();
            $mediaFile->setFile($filename[0] . '/' . $filename);
        }
    }

    public function postLoad(LifecycleEventArgs $args) {
        $entity = $args->getEntity();
        if (!$entity instanceof MediaFile) {
            return;
        }
        $filename = $entity->getFile();
        $path = $this->uploader->getUploadDir() . '/' . $filename;
        $entity->setFile(new File($path));
    }

    private function upload($entity) {
        if (!$entity instanceof MediaFile) {
            return;
        }
        $file = $entity->getFile();
        if (!$file instanceof UploadedFile) {
            return;
        }
        $filename = $this->uploader->upload($file);
        $entity->setFile($filename);
    }

}
