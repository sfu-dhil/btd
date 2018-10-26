<?php

namespace AppBundle\Services;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;

class FileUploader {

    private $uploadDir;

    public function __construct($uploadDir) {
        $this->uploadDir = $uploadDir;
    }

    public function upload(UploadedFile $file) {
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();
        $fs = new Filesystem();
        $path = $this->uploadDir . '/' . $fileName[0];
        if (!$fs->exists($path)) {
            $fs->mkdir($path);
        }
        $file->move($path, $fileName);
        return $fileName[0] . '/' . $fileName;
    }

    public function delete(File $file) {
        $fs = new Filesystem();
        if ($fs->exists($path = $this->uploadDir . '/' . $file)) {
            $fs->remove($file);
        }
    }

    public function getUploadDir() {
        return $this->uploadDir;
    }

    public function getMaxUploadSize($asBytes = false) {
        $maxBytes = UploadedFile::getMaxFilesize();
        if ($asBytes) {
            return $maxBytes;
        } else {
            $units = ['b', 'Kb', 'Mb', 'Gb', 'Tb'];
            $exp = floor(log($maxBytes, 1024));
            $est = round($maxBytes / pow(1024, $exp), 1);
            return $est . $units[$exp];
        }
    }

}
