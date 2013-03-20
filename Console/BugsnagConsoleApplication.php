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

		// Get container
		$container = $kernel->getContainer();

		// Setup Bugsnag to handle our errors
		\Bugsnag::register($container->getParameter('bugsnag.api_key'));
		\Bugsnag::setReleaseStage('development');
		\Bugsnag::setNotifyReleaseStages($container->getParameter('bugsnag.notify_stages'));
		\Bugsnag::setProjectRoot(realpath($container->getParameter('kernel.root_dir').'/..'));
	}

	public function renderException($e, $output)
	{
		// Send exception to Bugsnag
		\Bugsnag::notifyException($e);

		// Call parent function
		parent::renderException($e, $output);
	}
}