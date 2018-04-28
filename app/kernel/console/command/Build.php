<?php

namespace app\kernel\console\command;

use app\kernel\console\Command;
use app\kernel\console\Input;
use app\kernel\console\input\Option;
use app\kernel\console\Output;
use app\kernel\facade\App;
use app\kernel\facade\Build as AppBuild;

class Build extends Command
{

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('build')
            ->setDefinition([
                new Option('config', null, Option::VALUE_OPTIONAL, "build.php path"),
                new Option('module', null, Option::VALUE_OPTIONAL, "module name"),
            ])
            ->setDescription('Build Application Dirs');
    }

    protected function execute(Input $input, Output $output)
    {
        if ($input->hasOption('module')) {
            AppBuild::module($input->getOption('module'));
            $output->writeln("Successed");
            return;
        }

        if ($input->hasOption('config')) {
            $build = include $input->getOption('config');
        } else {
            $build = include App::getAppPath() . 'build.php';
        }

        if (empty($build)) {
            $output->writeln("Build Config Is Empty");
            return;
        }

        AppBuild::run($build);
        $output->writeln("Successed");

    }
}
