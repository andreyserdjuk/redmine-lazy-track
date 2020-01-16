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
                new InputOption(
                    'user',
                    'u',
                    InputOption::VALUE_REQUIRED,
                    'User id.'
                ),
                new InputOption(
                    'issue',
                    'i',
                    InputOption::VALUE_REQUIRED,
                    'Issue id.'
                ),
                new InputOption(
                    'message',
                    'm',
                    InputOption::VALUE_REQUIRED,
                    'Text message for issue tracking.'
                ),
                new InputOption(
                    'hours',
                    'ho',
                    InputOption::VALUE_OPTIONAL,
                    'Daily working hours.',
                    8
                ),
                new InputOption(
                    'activity',
                    'a',
                    InputOption::VALUE_OPTIONAL,
                    'Activity id, you can see it by inspecting <select> options.',
                    9 // Development
                ),
                new InputOption(
                    'start-date',
                    's',
                    InputOption::VALUE_OPTIONAL,
                    'Start date for tracking. Otherwise task will be tracked from last tracked day.'
                ),
                new InputOption(
                    'end-date',
                    'e',
                    InputOption::VALUE_OPTIONAL,
                    'End date for tracking. Otherwise task will be tracked up today.'
                ),
            ])
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $userId = $input->getOption('user');
        $issueId = $input->getOption('issue');
        $message = $input->getOption('message');
        $hours = $input->getOption('hours');
        $activityId = $input->getOption('activity');

        $startDate = $input->hasOption('start-date')
            ? $input->getOption('start-date')
            : self::getLastTrackedDateFromServer($userId, $issueId);

        $endDate = $input->hasOption('end-date')
            ? $input->getOption('end-date')
            : date('Y-m-d');

        $workingDays = self::getWorkingDays($startDate, $endDate);

        foreach ($workingDays as $workingDay) {
            $date = $workingDay->format('Y-m-d');
            try {
                self::getClient()->time_entry->create([
                    'issue_id'    => $issueId,
                    'spent_on'    => $date,
                    'hours'       => $hours,
                    'activity_id' => $activityId,
                    'comments'    => $message,
                ]);
                $output->writeln(sprintf('Created issue tracking at %s', $date));
            } catch (\Exception $e) {
                $output->writeln(sprintf('Failed  issue tracking at %s', $date));
            }
        }
    }

    /**
     * @param $lastDate
     * @param $currentDate
     * @return DateTime[]
     */
    protected static function getWorkingDays($lastDate, $currentDate)
    {
        $currentDate = date_create_from_format('Y-m-d', $currentDate);
        $lastDate = date_create_from_format('Y-m-d', $lastDate);

        $days = [];
        while ($lastDate < $currentDate) {
            $lastDate->modify('+1 day');
            if ($lastDate->format('N') < 6) {
                $days[] = clone $lastDate;
            }
        }

        return $days;
    }

    /**
     * Returns last tracked issue activity date.
     *
     * @param string $userId
     * @param string $issueId
     * @return string
     */
    protected static function getLastTrackedDateFromServer(string $userId, string $issueId): string
    {
        $recentEntries = self::getClient()->time_entry->all([
            'user_id' => $userId,
            'limit'   => 10,
            'issue'   => $issueId,
        ]);

        $lastEntry = current($recentEntries['time_entries']);

        return $lastEntry['spent_on'];
    }
}
