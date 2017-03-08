<?php

namespace AppBundle\Command;

use AppBundle\Entity\MediaFile;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AppGenerateThumbnailsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:generate:thumbnails')
            ->setDescription('Generate thumbnails for media files.')
        ;
    }
    
    protected function generateThumbnail(MediaFile $mediaFile) {
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $mediaFiles = $em->getRepository(MediaFile::class)->findAll();
        foreach($mediaFiles as $mediaFile) {
            $this->generateThumbnail($mediaFile);
        }
    }

}
