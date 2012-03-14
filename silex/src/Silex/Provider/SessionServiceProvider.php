<?php










namespace Silex\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;

use Symfony\Component\HttpFoundation\Session\Storage\NativeFileSessionStorage;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\KernelEvents;






class SessionServiceProvider implements ServiceProviderInterface
{
    private $app;

    public function register(Application $app)
    {
        $this->app = $app;

        $app['session'] = $app->share(function () use ($app) {
            return new Session($app['session.storage']);
        });

        $app['session.storage'] = $app->share(function () use ($app) {
            return new NativeFileSessionStorage(
                isset($app['session.storage.save_path']) ? $app['session.storage.save_path'] : null,
                $app['session.storage.options']
            );
        });

        $app['dispatcher']->addListener(KernelEvents::REQUEST, array($this, 'onKernelRequest'), 128);

        if (!isset($app['session.storage.options'])) {
            $app['session.storage.options'] = array();
        }

        if (!isset($app['session.default_locale'])) {
            $app['session.default_locale'] = 'en';
        }
    }

    public function onKernelRequest($event)
    {
        $request = $event->getRequest();
        $request->setSession($this->app['session']);

        
        if ($request->hasPreviousSession()) {
            $request->getSession()->start();
        }
    }
}
