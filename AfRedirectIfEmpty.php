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
        $controller = $args->getSubject();
        $view = $controller->View();
        $req = $controller->Request();
        $reqParams = $req->getParams();
        $pageRequest = $reqParams['sPage'];
        $articles = $view->getAssign('sArticles');
        $initialUri = $req->getRequestUri();
        $pathInfo= $req->getPathInfo();
        $host = Shopware()->Shop()->getHost();

        // wie hearusfinden ob http oder https - vllt auch voellig falscher Ansatz
        // sollte aber ja eh durch die htaccess abgefangen werden
        $finalUrl = ("http://" . $host . $pathInfo);

        // wenn p=x größer eins und keine article vorhanden dann redirect
        if($pageRequest > 1 && !$articles){
            // setze response code auf 410 | 404 - das koennte man dann im backend konfigurierbar machen
            http_response_code(410);
            // leite weiter auf die Kategorieseite ohne p=x
            header("Location:" . $finalUrl);
            exit;
        }
    }

}
