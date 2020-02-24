<?php

namespace App\Command;

use App\Entity\MediaFile;
use App\Entity\Project;
use App\Entity\ProjectContribution;
use App\Entity\ProjectPage;
use App\Entity\Venue;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Psr\Log\LoggerInterface;
use SplFileInfo;
use Symfony\Bridge\Twig\TwigEngine;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ExportIslandoraCommand extends Command {
    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * @var TwigEngine
     */
    private $twig;

    protected function configure() {
        $this
            ->setName('app:export:islandora')
            ->setDescription('Export a project as an islandora collection.')
            ->addArgument('project-id', InputArgument::REQUIRED, 'Database ID of the project to export.')
            ->addArgument('path', InputArgument::REQUIRED, 'File system path to export to.')
        ;
    }

    /**
     * @param EntityManagerInterface $em
     *
     * @required
     */
    public function setEntityManager(EntityManagerInterface $em) {
        $this->em = $em;
    }

    /**
     * @param LoggerInterface $logger
     *
     * @required
     */
    public function setLogger(LoggerInterface $logger) {
        $this->logger = $logger;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $project = $this->em->find(Project::class, $input->getArgument('project-id'));
        $dh = $this->getDirHandle($input->getArgument('path'));
        $output->writeln("exporting {$project} to {$dh->getRealPath()}");
        $this->exportProject($project, $dh);
    }

    /**
     * @param string $path
     *
     * @throws Exception
     *
     * @return SplFileInfo
     */
    public function getDirHandle($path) {
        if ( ! file_exists($path)) {
            mkdir($path);
        }
        $dh = new SplFileInfo($path);
        if ( ! $dh->isDir()) {
            throw new Exception("{$path} is not a directory.");
        }
        if ( ! $dh->isWritable()) {
            throw new Exception("{$path} is not writable.");
        }

        return $dh;
    }

    public function writeCollection(Project $project, SplFileInfo $dh) {
        $fh = fopen($dh->getRealPath() . '/collection_dc.xml', 'w');
        $xml = $this->twig->render('App:Project:dc.xml.twig', array('project' => $project));
        fwrite($fh, $xml);
        fclose($fh);
    }

    public function exportPage(ProjectPage $page, SplFileInfo $dh) {
        $dcHandle = fopen($dh->getRealPath() . "/page_{$page->getId()}_DC.xml", 'w');
        $xml = $this->twig->render('App:ProjectPage:dc.xml.twig', array(
            'page' => $page,
        ));
        fwrite($dcHandle, $xml);
        fclose($dcHandle);

        $contentHandle = fopen($dh->getRealPath() . "/page_{$page->getId()}.html", 'w');
        fwrite($contentHandle, $page->getContent());
    }

    public function exportMediaFile(MediaFile $mediaFile, SplFileInfo $dh) {
        $dcHandle = fopen($dh->getRealPath() . "/media_{$mediaFile->getBasename()}_DC.xml", 'w');
        $xml = $this->twig->render('App:MediaFile:dc.xml.twig', array(
            'mediaFile' => $mediaFile,
        ));
        fwrite($dcHandle, $xml);
        fclose($dcHandle);

        copy($mediaFile->getRealPath(), "{$dh->getRealPath()}/media_{$mediaFile->getFilename()}");
    }

    public function exportContribution(ProjectContribution $contribution, SplFileInfo $dh) {
        $dcHandle = fopen($dh->getRealPath() . "/{$contribution->getProjectRole()->getName()}_{$contribution->getId()}_DC.xml", 'w');
        $xml = $this->twig->render('App:ProjectContribution:dc.xml.twig', array(
            'contribution' => $contribution,
        ));
        fwrite($dcHandle, $xml);
        fclose($dcHandle);

        $contentHandle = fopen($dh->getRealPath() . "/{$contribution->getProjectRole()->getName()}_{$contribution->getId()}.html", 'w');
        if ($contribution->getPerson()) {
            fwrite($contentHandle, $contribution->getPerson()->getBiography());
        }
        if ($contribution->getOrganization()) {
            fwrite($contentHandle, $contribution->getOrganization()->getDescription());
        }
        echo "\n";
    }

    public function exportVenue(Venue $venue) {
        echo $venue . "\n";
    }

    public function exportProject(Project $project, SplFileInfo $dh) {
        $this->writeCollection($project, $dh);

        foreach ($project->getProjectPages() as $page) {
            $this->exportPage($page, $dh);
        }
        foreach ($project->getMediaFiles() as $mediaFile) {
            $this->exportMediaFile($mediaFile, $dh);
        }
        foreach ($project->getContributions() as $contribution) {
            $this->exportContribution($contribution, $dh);
        }
        foreach ($project->getVenues() as $venue) {
            $this->exportVenue($venue);
        }
    }
}
