<?php
namespace Wrep\Bundle\BugsnagBundle\Console;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BugsnagConsoleApplication extends Application
{
	public function __construct(KernelInterface $kernel)
	{
		parent::__construct($kernel);

		// Boot kernel now
		$kernel->boot();

		// Setup Bugsnag to handle our errors
		\Bugsnag::register($kernel->getContainer()->getParameter('bugsnag.api_key'));
		\Bugsnag::setReleaseStage('development');
		\Bugsnag::setNotifyReleaseStages(array('development'));
		\Bugsnag::setProjectRoot(realpath($kernel->getContainer()->getParameter('kernel.root_dir').'/..'));
	}

	public function renderException($e, $output)
	{
		// Send exception to Bugsnag
		\Bugsnag::notifyException($e);

		// Call parent function
		parent::renderException($e, $output);
	}
}