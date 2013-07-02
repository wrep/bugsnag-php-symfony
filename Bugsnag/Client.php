<?php
namespace Wrep\Bundle\BugsnagBundle\Bugsnag;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * The BugsnagBundle Client Loader.
 *
 * This class assists in the loading of the bugsnag Client class.
 *
 * @license		http://www.opensource.org/licenses/mit-license.php
 */
class Client
{
    protected $enabled = false;

    /**
     * @param string $apiKey
     * @param Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param string|null $queue
     */
    public function __construct($apiKey, $envName, ContainerInterface $container)
    {
        if (!$apiKey) {
            return;
        }

        $this->enabled = true;
        $request = $container->get('request');
        $releaseStage = ($envName == 'prod') ? 'production' : $envName;

        // Register bugsnag
        \Bugsnag::register($apiKey);
        \Bugsnag::setReleaseStage($releaseStage);
        \Bugsnag::setNotifyReleaseStages($container->getParameter('bugsnag.notify_stages'));
        \Bugsnag::setProjectRoot(realpath($container->getParameter('kernel.root_dir').'/..'));
        \Bugsnag::setMetaDataFunction(function() use ($request) {
            // Set up result array
            $metaData = array(
                'Symfony' => array()
            );

            // Get and add controller information, if available
            $controller = $request->attributes->get('_controller');
            if ($controller !== null)
            {
                $metaData['Symfony'] = array('Controller' => $controller);
            }

            // Return our metadata to be included in the error message
            return $metaData;
        });
    }

    public function notifyOnException(\Exception $e)
    {
    	if ($this->enabled) {
    		\Bugsnag::notifyException($e);
    	}
    }

    public function notifyOnError($message, Array $metadata = null)
    {
    	if ($this->enabled) {
    		\Bugsnag::notifyError('Error', $message, $metadata);
    	}
    }
}
