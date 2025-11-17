<?php

namespace App\Command;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:fix-feed-urls',
    description: 'Fix feedUrl for existing users by adding their userId',
)]
class FixFeedUrlsCommand extends Command
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $em
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $users = $this->userRepository->findAll();
        $fixed = 0;

        foreach ($users as $user) {
            $currentUrl = $user->getFeedUrl();
            
            // Skip if no feedUrl or already correct
            if (!$currentUrl) {
                continue;
            }
            
            // Check if URL already contains userId
            $expectedUrl = "http://caddy/fake-provider/" . $user->getId();
            if ($currentUrl === $expectedUrl) {
                continue;
            }
            
            // Fix the URL
            $user->setFeedUrl($expectedUrl);
            $this->em->persist($user);
            $output->writeln("Fixed feedUrl for user {$user->getEmail()} (ID: {$user->getId()}): {$currentUrl} -> {$expectedUrl}");
            $fixed++;
        }

        if ($fixed > 0) {
            $this->em->flush();
            $output->writeln("<info>Fixed {$fixed} user feedUrl(s)</info>");
        } else {
            $output->writeln("<info>All feedUrls are already correct</info>");
        }

        return Command::SUCCESS;
    }
}

