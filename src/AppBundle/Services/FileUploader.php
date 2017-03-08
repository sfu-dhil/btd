<?php

namespace AppBundle\Services;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader {
    
    private $uploadDir;
    
    public function __construct($uploadDir) {
        $this->uploadDir = $uploadDir;
    }
    
    public function upload(UploadedFile $file)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        $fs = new Filesystem();
        $path = $this->uploadDir . '/' . $fileName[0];
        if( ! $fs->exists($path)) {
            $fs->mkdir($path);
        }
        $file->move($path, $fileName);
        return $fileName[0] . '/' . $fileName;
    }
    
    public function delete($filepath) {
        $path = $this->uploadDir . '/' . $filepath;
        $fs = new Filesystem();
        if($fs->exists($path)) {
            $fs->remove($path);
        }
    }
    
    public function getUploadDir() {
        return $this->uploadDir;
    }
    
    
    
}
