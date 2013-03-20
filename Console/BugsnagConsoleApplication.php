<?php
namespace Wrep\Bundle\BugsnagBundle\Console;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\HttpKernel\KernelInterface;

class BugsnagConsoleApplication extends Application
{
	public function __construct(KernelInterface $kernel)
	{
		// Pass the kernel to our parent
		parent::__construct($kernel);

		// Setup Bugsnag to handle our errors

	}

	public function renderException($e, $output)
	{
		// Send exception to Bugsnag
		\Bugsnag::notifyException($e);

		// Call parent function
		parent::renderException($e, $output);
	}
}