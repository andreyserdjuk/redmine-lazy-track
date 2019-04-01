<?php

use RedmineLogger\Command\ListLastTrackings;
use RedmineLogger\Command\LogWorkingDays;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Output\ConsoleOutput;

require __DIR__ . '/../vendor/autoload.php';

$app = new Application('Lazy Redmine', '0.0.1');

$app->add(new LogWorkingDays());
$app->add(new ListLastTrackings());

$app->run(new Symfony\Component\Console\Input\ArgvInput(), new ConsoleOutput());