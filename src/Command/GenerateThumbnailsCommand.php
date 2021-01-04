<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Command;

use App\Entity\MediaFile;
use App\Utility\Thumbnailer;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateThumbnailsCommand extends Command {
    protected function configure() : void {
        $this
            ->setName('app:generate:thumbnails')
            ->setDescription('Generate thumbnails for media files.')
            ->addOption('force', null, InputOption::VALUE_NONE, 'Regenerate all thumbnails.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) : void {
        exit('NOT IMPLEMENTED.');
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $thumbnailer = new Thumbnailer();

        $mediaFiles = [];
        if ($input->getOption('force')) {
            $mediaFiles = $em->getRepository(MediaFile::class)->findAll();
        } else {
            $mediaFiles = $em->getRepository(MediaFile::class)->findBy([
                'hasThumbnail' => false,
            ]);
        }

        foreach ($mediaFiles as $mediaFile) {
            $output->writeln($mediaFile->getId() . ':' . $mediaFile->getFilename());

            try {
                $thumbnailer->generateThumbnail($mediaFile);
                $mediaFile->setHasThumbnail(true);
                $em->flush($mediaFile);
            } catch (Exception $e) {
                $output->writeln($e->getMessage());
            }
        }
    }
}
