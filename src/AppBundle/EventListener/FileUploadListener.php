<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\MediaFile;
use AppBundle\Services\FileUploader;
use AppBundle\Utility\Thumbnailer;
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
        if ($args->getEntity() instanceof MediaFile) {
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
        if (file_exists($path)) {
            $entity->setFile(new File($path));
        } else {
            $entity->setFile(new File($this->uploader->getUploadDir() . '/../misc/missing-file.jpg'));
        }
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
