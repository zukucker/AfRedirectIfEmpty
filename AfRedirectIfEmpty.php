<?php

namespace AfRedirectIfEmpty;

use Shopware\Components\Plugin;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Shopware-Plugin AfRedirectIfEmpty.
 */
class AfRedirectIfEmpty extends Plugin
{

    /**
    * @param ContainerBuilder $container
    */
    public function build(ContainerBuilder $container)
    {
        $container->setParameter('af_redirect_if_empty.plugin_dir', $this->getPath());
        parent::build($container);
    }
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatch_Frontend_Listing' => 'onFrontend',
        ];
    }

    public function onFrontend(\Enlight_Event_EventArgs $args)
    {
        $config = $this->container->get('shopware.plugin.cached_config_reader')->getByPluginName($this->getName());
        $code = $config['AfRedirectIfEmptyCode'];
        if(!$code){
            $code = 410;
        }

        $controller = $args->getSubject();
        $view = $controller->View();
        $req = $controller->Request();
        $reqParams = $req->getParams();
        $pageRequest = $reqParams['sPage'];
        $articles = $view->getAssign('sArticles');
        $initialUri = $req->getRequestUri();
        $pathInfo= $req->getPathInfo();
        $host = Shopware()->Shop()->getHost();

        // TODO: detect if http oder https
        // should be done with the .htaccess
        $finalUrl = ("http://" . $host . $pathInfo);

        // if p=x greater 1 and no articles found redirect
        if($pageRequest > 1 && !$articles){
            // redirect to category main page without p=x
            header("Location:" . $finalUrl, true, $code);
            exit;
        }
    }

}
