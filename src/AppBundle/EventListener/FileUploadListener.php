<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\MediaFile;
use AppBundle\Services\FileUploader;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;

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
        $this->upload($args->getEntity());
    }

    public function postLoad(LifecycleEventArgs $args) {
        $entity = $args->getEntity();
        if (!$entity instanceof MediaFile) {
            return;
        }
        $directory = $this->uploader->getUploadDir();
        if (($filename = $entity->getFile())) {
            if (substr($filename, 0, strlen($directory)) === $directory) {
                $filename = substr($filename, strlen($directory));
            }
            try {
                $entity->setFile(new File($directory . '/' . $filename));
            } catch(\Exception $e) {
                $entity->setFile(new File($directory . '/../misc/missing-file.jpg'));
            }
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
        $directory = $this->uploader->getUploadDir();
        if (substr($filename, 0, strlen($directory)) === $directory) {
            $filename = substr($filename, strlen($directory));
        }
        $entity->setFile($filename);
    }

}
