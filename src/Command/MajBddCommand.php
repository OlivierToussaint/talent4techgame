<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MajBddCommand extends Command
{
    protected static $defaultName = 'app:maj-bdd';
    private $userRepository;
    private $entityManager;

    public function __construct(string $name = null, UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        parent::__construct($name);
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }



    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // creates a new progress bar (50 units)
        $progressBar = new ProgressBar($output, 100000);

        // starts and displays the progress bar
        $progressBar->start();

        $i = 0;
        while ($i++ < 100000) {
            // ... do some work

            // advances the progress bar 1 unit
            $progressBar->advance();

            // you can also advance the progress bar by more than 1 unit
            // $progressBar->advance(3);
        }

        // ensures that the progress bar is at 100%
        $progressBar->finish();

        // Doit ramener les utilisateurs qui ne se sont pas connecter
        $users = $this->userRepository->findNoConnexion();
        if (count($users) > 0) {
            foreach($users as $user) {
                if ($user instanceof User) {
                    $this->entityManager->remove($user);
                }
            }
            $this->entityManager->flush();
            $io->success('Tout est bien supprimé');

        } else {
            $io->success('Il n\'y avait rien à supprimer');

        }


        return 0;
    }
}
