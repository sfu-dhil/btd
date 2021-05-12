<?php

declare(strict_types=1);

/*
 * (c) 2021 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Services;

use App\Entity\MediaFile;
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

    protected function thumb(Imagick $magick, $path, $basename) : void {
        $magick->cropThumbnailImage($this->width, $this->height);
        $magick->setImageFormat('jpg');
        $handle = fopen(dirname($path) . '/' . $basename . '_tn.jpg', 'wb');
        fwrite($handle, $magick->getimageblob());
    }

    protected function thumbnailImage(MediaFile $mediaFile) : void {
        $path = $mediaFile->getFile()->getRealPath();
        $magick = new Imagick($path);
        $this->thumb($magick, $path, $mediaFile->getBasename());
    }

    protected function thumbnailPdf(MediaFile $mediaFile) : void {
        $path = $mediaFile->getFile()->getRealPath();
        $magick = new Imagick($path . '[0]');
        $this->thumb($magick, $path, $mediaFile->getBasename());
    }

    protected function thumbnailVideo(MediaFile $mediaFile) : void {
        $path = $mediaFile->getFile()->getRealPath();
        $magick = new Imagick($path . '[0]');
        $this->thumb($magick, $path, $mediaFile->getBasename());
    }

    protected function thumbnailAudio(MediaFile $mediaFile) : void {
        $path = $mediaFile->getFile()->getRealPath();
        copy(dirname($path) . '/../misc/audio_tn.jpg', dirname($path) . '/' . $mediaFile->getBasename() . '_tn.jpg');
    }

    public function generateThumbnail(MediaFile $mediaFile) : void {
        $mime = $mediaFile->getMimeType();
        if ('image/' === mb_substr($mime, 0, 6)) {
            $this->thumbnailImage($mediaFile);
        }
        if ('video/' === mb_substr($mime, 0, 6)) {
            $this->thumbnailVideo($mediaFile);
        }
        if ('audio/' === mb_substr($mime, 0, 6)) {
            $this->thumbnailAudio($mediaFile);
        }
        if ('application/pdf' === $mime) {
            $this->thumbnailPdf($mediaFile);
        }
    }
}
