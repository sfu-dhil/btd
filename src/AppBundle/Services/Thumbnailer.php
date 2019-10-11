<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Services;

use AppBundle\Entity\MediaFile;
use Imagick;

/**
 * Description of Thumbnailer.
 *
 * @author michael
 */
class Thumbnailer {
    private $width;

    private $height;

    public function __construct($width, $height) {
        $this->width = $width;
        $this->height = $height;
    }

    protected function thumb(Imagick $magick, $path, $basename) {
        $magick->cropThumbnailImage($this->width, $this->height);
        $magick->setImageFormat('jpg');
        $handle = fopen(dirname($path) . '/' . $basename . '_tn.jpg', 'wb');
        fwrite($handle, $magick->getimageblob());
    }

    protected function thumbnailImage(MediaFile $mediaFile) {
        $path = $mediaFile->getFile()->getRealPath();
        $magick = new Imagick($path);
        $this->thumb($magick, $path, $mediaFile->getBasename());
    }

    protected function thumbnailPdf(MediaFile $mediaFile) {
        $path = $mediaFile->getFile()->getRealPath();
        $magick = new Imagick($path . '[0]');
        $this->thumb($magick, $path, $mediaFile->getBasename());
    }

    protected function thumbnailVideo(MediaFile $mediaFile) {
        $path = $mediaFile->getFile()->getRealPath();
        $magick = new Imagick($path . '[0]');
        $this->thumb($magick, $path, $mediaFile->getBasename());
    }

    protected function thumbnailAudio(MediaFile $mediaFile) {
        $path = $mediaFile->getFile()->getRealPath();
        copy(dirname($path) . '/../misc/audio_tn.jpg', dirname($path) . '/' . $mediaFile->getBasename() . '_tn.jpg');
    }

    public function generateThumbnail(MediaFile $mediaFile) {
        $mime = $mediaFile->getMimeType();
        if ('image/' === substr($mime, 0, 6)) {
            $this->thumbnailImage($mediaFile);
        }
        if ('video/' === substr($mime, 0, 6)) {
            $this->thumbnailVideo($mediaFile);
        }
        if ('audio/' === substr($mime, 0, 6)) {
            $this->thumbnailAudio($mediaFile);
        }
        if ('application/pdf' === $mime) {
            $this->thumbnailPdf($mediaFile);
        }
    }
}
