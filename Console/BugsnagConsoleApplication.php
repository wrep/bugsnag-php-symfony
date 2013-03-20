<?php
namespace Wrep\Bundle\BugsnagBundle\Console;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BugsnagConsoleApplication extends Application
{
	public function renderException($e, $output)
	{
		// Setup Bugsnag to handle our errors
		\Bugsnag::register($this->getKernel()->getContainer()->getParameter('bugsnag.apikey'));
		\Bugsnag::setReleaseStage('development');
		\Bugsnag::setNotifyReleaseStages(array('development'));
		\Bugsnag::setProjectRoot(realpath($this->getKernel()->getContainer()->getParameter('kernel.root_dir').'/..'));

		// Send exception to Bugsnag
		\Bugsnag::notifyException($e);

		// Call parent function
		parent::renderException($e, $output);
	}
}