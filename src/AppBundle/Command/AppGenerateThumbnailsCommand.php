<?php

namespace AppBundle\Command;

use AppBundle\Entity\MediaFile;
use Imagick;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AppGenerateThumbnailsCommand extends ContainerAwareCommand
{
    private $thumbnailSize;
    
    private $uploadDir;
    
    protected function configure()
    {
        $this
            ->setName('app:generate:thumbnails')
            ->setDescription('Generate thumbnails for media files.')
        ;
    }
    
    public function setContainer(ContainerInterface $container = null) {
        parent::setContainer($container);
        $this->thumbnailSize = $container->getParameter('btd.media_thumbnail_size');
    }
    
    protected function thumbnailImage(MediaFile $mediaFile) {
        $path = $mediaFile->getFile()->getRealPath();
        $magick = new Imagick($path);
        $magick->resizeImage($this->thumbnailSize, $this->thumbnailSize, imagick::FILTER_BOX, 0, true);
        $handle = fopen(dirname($path) . '/' . $mediaFile->getBasename() . '_tn.jpg', 'wb');
        fwrite($handle, $magick->getimageblob());
    }
    
    protected function thumbnailPdf(MediaFile $mediaFile) {
        $path = $mediaFile->getFile()->getRealPath();
        $magick = new Imagick($path . '[0]');     
        $magick->resizeImage($this->thumbnailSize, $this->thumbnailSize, imagick::FILTER_BOX, 0, true);
        $magick->setImageFormat('jpg');
        $handle = fopen(dirname($path) . '/' . $mediaFile->getBasename() . '_tn.jpg', 'wb');
        fwrite($handle, $magick->getimageblob());        
    }
    
    protected function thumbnailVideo(MediaFile $mediaFile) {
        $path = $mediaFile->getFile()->getRealPath();
        $magick = new Imagick($path . '[0]');     
        $magick->resizeImage($this->thumbnailSize, $this->thumbnailSize, imagick::FILTER_BOX, 0, true);
        $magick->setImageFormat('jpg');
        $handle = fopen(dirname($path) . '/' . $mediaFile->getBasename() . '_tn.jpg', 'wb');
        fwrite($handle, $magick->getimageblob());        
    }
    
    protected function thumbnailAudio(MediaFile $mediaFile) {
        $path = $mediaFile->getFile()->getRealPath();
        copy(dirname($path) . '/../misc/audio_tn.jpg', dirname($path) . '/' . $mediaFile->getBasename() . '_tn.jpg');
    }
    
    protected function generateThumbnail(MediaFile $mediaFile) {
        $mime = $mediaFile->getMimeType();
        if(substr($mime, 0, 6) === 'image/') {
            $this->thumbnailImage($mediaFile);
        }
        if(substr($mime, 0, 6) === 'video/') {
            $this->thumbnailVideo($mediaFile);
        }
        if(substr($mime, 0, 6) === 'audio/') {
            $this->thumbnailAudio($mediaFile);
        }
        if($mime === 'application/pdf') {
            $this->thumbnailPdf($mediaFile);
        }        
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $mediaFiles = $em->getRepository(MediaFile::class)->findAll();
        foreach($mediaFiles as $mediaFile) {
            $output->writeln($mediaFile->getId() . ':' . $mediaFile->getFilename());
            try {
                $this->generateThumbnail($mediaFile);
            } catch(\Exception $e) {
                $output->writeln($e->getMessage());
            }
        }
    }

}
