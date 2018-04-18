<?php
namespace Application\Service;
use Zend\Session\Container;
use Application\Service\GeoPlugin;
use Zend\Http\Header\SetCookie;

/**
 * The TranslationManager service is responsible for setting the language when
 * someone visit the site without logging in.
 */
class TranslationManager
{
    private $countryLanguage = [
        'US' => 'en_US',
        'AR' => 'es_ES',
        'BO' => 'es_ES',
        'CL' => 'es_ES',
        'CO' => 'es_ES',
        'CR' => 'es_ES',
        'CU' => 'es_ES',
        'DO' => 'es_ES',
        'EC' => 'es_ES',
        'SV' => 'es_ES',
        'GQ' => 'es_ES',
        'GT' => 'es_ES',
        'HN' => 'es_ES',
        'MX' => 'es_ES',
        'NI' => 'es_ES',
        'PA' => 'es_ES',
        'PY' => 'es_ES',
        'PE' => 'es_ES',
        'ES' => 'es_ES',
        'UY' => 'es_ES',
        'VE' => 'es_ES'
    ];
    
    public function initTranslator($event, $serviceManager, $sessionManager)
    {
        
        $sessionContainer = new Container('Language', $sessionManager);
        $lang = $sessionContainer->Language;

        // If language is not set in the user settings use the logged out language drop down.
        if (!$lang) {
            $lang = $event->getRequest()->getCookie()->xuage;
        }
        
        // If language is not set in the user settings or logged out drop down.
        if (!$lang) {
           $lang = $this->getGeoLanguage($event);
        }
   
        // If language is not set by the above three methods.
        if (!$lang) {
            $lang = 'en_US';
        }

        $translator = $serviceManager->get('MvcTranslator');
        $translator
            ->setLocale($lang)
            ->setFallbackLocale('en_US');
    }
    
    public function getGeoLanguage(&$object)
    {
        $lang = null;
        // Use geolocation to create a geolocation cookie if it does not exist.
        // If it exists use the value from the cookie.
        // Include geoPlugin class here as well a country code to locale lookup array.
        $geoCookie = $object->getRequest()->getCookie()->geoLanguage;
        if (!$geoCookie) {
            $geoplugin = new geoPlugin();
            $geoplugin->locate();
            if (array_key_exists($geoplugin->countryCode, $this->countryLanguage)){
                $lang = $this->countryLanguage[$geoplugin->countryCode];
                $cookie = new SetCookie(geoLanguage, $lang);
                $object->getResponse()->getHeaders()->addHeader($cookie);
            }
        }else{
            $lang = $object->getRequest()->getCookie()->geoLanguage;
        }  
        
        return $lang;
        
    }
}
