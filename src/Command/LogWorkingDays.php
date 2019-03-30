<?php


namespace RedmineLogger\Command;


use DateTime;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class LogWorkingDays extends Command
{
    use ClientTrait;

    protected static $defaultName = 'redmine:track';

    protected function configure()
    {
        $this->setDescription('Lazy log all working days before current with message');
        $this->setDefinition(
            new InputDefinition([
                new InputOption('user', 'u', InputOption::VALUE_REQUIRED),
                new InputOption('issue', 'i', InputOption::VALUE_REQUIRED),
                new InputOption('message', 'm', InputOption::VALUE_REQUIRED),
            ])
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $userId = $input->getOption('user');
        $issueId = $input->getOption('issue');
        $message = $input->getOption('message');

        $recentEntries = self::getClient()->time_entry->all([
            'user_id' => $userId,
            'limit' => 10,
            'issue' => 11854,
        ]);

        $lastEntry = current($recentEntries['time_entries']);
        $lastDate = $lastEntry['spent_on'];
        $currentDate = date('Y-m-d');
        $workingDays = self::getWorkingDays($lastDate, $currentDate);

        foreach ($workingDays as $workingDay) {
            self::getClient()->time_entry->create([
                'issue_id' => $issueId,
                'spent_on' => $workingDay->format('Y-m-d'),
                'hours' => 8,
                'activity_id' => 9, // Development
                'comments' => $message,
            ]);
        }
    }

    /**
     * @param $lastDate
     * @param $currentDate
     * @return DateTime[]
     */
    protected static function getWorkingDays($lastDate, $currentDate) {
        $currentDate = date_create_from_format('Y-m-d', $currentDate);
        $lastDate = date_create_from_format('Y-m-d', $lastDate);

        $days = [];
        while ($lastDate <= $currentDate) {
            $next = $lastDate->modify('+1 day');
            if ($next->format('N') < 7) {
                $days[] = $next;
            }
        }

        return $days;
    }
}