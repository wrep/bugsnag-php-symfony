<?php
namespace Wrep\Bundle\BugsnagBundle\Console;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BugsnagConsoleApplication extends Application
{
	public function run(InputInterface $input = null, OutputInterface $output = null)
	{
		// Setup Bugsnag to handle our errors
		\Bugsnag::register($this->getKernel()->getContainer()->getParameter('bugsnag.apikey'));
		\Bugsnag::setReleaseStage('development');
		\Bugsnag::setNotifyReleaseStages(array('development'));
		\Bugsnag::setProjectRoot(realpath($this->getKernel()->getContainer()->getParameter('kernel.root_dir').'/..'));

		// Run command now
		parent::run($input, $output);
	}

	public function renderException($e, $output)
	{
		// Send exception to Bugsnag
		\Bugsnag::notifyException($e);

		// Call parent function
		parent::renderException($e, $output);
	}
}