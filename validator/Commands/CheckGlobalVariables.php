<?php
/**
 * Created by PhpStorm.
 * User: sx
 * Date: 2017/10/23
 * Time: 19:39
 */

namespace Zsxsoft\AppValidator\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zsxsoft\AppValidator\Helpers\Logger;
use Zsxsoft\AppValidator\Helpers\TempHelper;
use Zsxsoft\AppValidator\Tasks\GlobalVariables;
use Zsxsoft\AppValidator\Wrappers\ZBPWrapper;

class CheckGlobalVariables extends Command
{

    protected function configure()
    {
        $this
            ->setName('check name')
            ->setDescription('Check name of global variables of app')
            ->addArgument(
                'appId',
                InputArgument::REQUIRED,
                'App ID'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $appId = $input->getArgument("appId");
        ZBPWrapper::loadApp($appId);
        (new GlobalVariables())->run();
    }
}