<?php


namespace RedmineLogger\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ListLastTrackings extends Command
{
    use ClientTrait;

    protected static $defaultName = 'redmine:list-trackings';

    protected function configure()
    {
        $this->setDescription('List prev tracking.');
        $this->setDefinition(
            new InputDefinition([
                new InputOption('user', 'u', InputOption::VALUE_REQUIRED),
            ])
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $userId = $input->getOption('user');

        $table = new Table($output);
        $table->setHeaders(['id', 'project', 'issue', 'activity', 'spent_on', 'hours']);


        $recentEntries = self::getClient()->time_entry->all([
            'user_id' => $userId,
            'limit' => 10,
        ]);

        foreach ($recentEntries['time_entries'] as $recentEntry) {
            $row = [
                'id' => $recentEntry['id'],
                'project' => $recentEntry['project']['name'],
                'issue' => $recentEntry['issue']['id'],
                'activity' => $recentEntry['activity']['name'],
                'spent_on' => $recentEntry['spent_on'],
                'hours' => $recentEntry['hours'],
            ];
            $table->addRow($row);
        }

        $table->render();
    }
}