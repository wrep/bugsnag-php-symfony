<?php
namespace Wrep\Bundle\BugsnagBundle\Bugsnag;

//use Airbrake\Client as AirbrakeClient;
//use Airbrake\Notice;
//use Airbrake\Configuration as AirbrakeConfiguration;
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
        $request       = $container->get('request');
        $controller    = 'None';
        $action        = 'None';

        if ($sa = $request->attributes->get('_controller')) {
            $controllerArray = explode('::', $sa);
            if(sizeof($controllerArray) > 1){
                list($controller, $action) = $controllerArray;
            }
        }

        // Register bugsnag
        \Bugsnag::register($apiKey);
        \Bugsnag::setReleaseStage($envName);
        \Bugsnag::setNotifyReleaseStages(array("dev", "prod")); // @TODO: make this a setting
        \Bugsnag::setProjectRoot(realpath($container->getParameter('kernel.root_dir').'/..'));

/*
        $options = array(
            'environmentName' => $envName,
            'queue'           => $queue,
            'serverData'      => $request->server->all(),
            'getData'         => $request->query->all(),
            'postData'        => $request->request->all(),
            'sessionData'     => $request->getSession() ? $request->getSession()->all() : null,
            'component'       => $controller,
            'action'          => $action,
            'projectRoot'     => realpath($container->getParameter('kernel.root_dir').'/..'),
        );*/
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
