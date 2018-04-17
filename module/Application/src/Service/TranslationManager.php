<?php
namespace Application\Service;
use Zend\Session\Container;
use Application\Service\GeoPlugin;

/**
 * The TranslationManager service is responsible for adding new posts, updating existing
 * posts, adding tags to post, etc.
 */
class TranslationManager
{
    
    public function initTranslator($event, $serviceManager, $sessionManager)
    {
        
        $sessionContainer = new Container('Language', $sessionManager);
        $lang = $sessionContainer->Language;

        //if language is not set in the session
        if (!$lang) {
            $lang = $event->getRequest()->getCookie()->xuage;
        }
        
        //if language is not set in the session or cookie
        if (!$lang) {
            //use geolocation to create a geolocation cookie if it does not exist
            // if it exists use the value from the cookie
            //include geoplugun class here as well a country code to locale lookup array
            $geoCookie = $event->getRequest()->getCookie()->geoCookie;
            if (!$geoCookie) {
                $geoplugin = new geoPlugin();
                $geoplugin->locate();
                
                
            }
 
        }
        
        //if language is not set in the session or cookie or by geolocation
        if (!$lang) {
            $lang = 'en_US';
        }

        $translator = $serviceManager->get('MvcTranslator');
        $translator
            ->setLocale($lang)
            ->setFallbackLocale('en_US');
    }
    
    
    
}
