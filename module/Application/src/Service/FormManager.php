<?php
namespace Application\Service;

/**
 * This service is responsible for form tasks such as translating form error messages.
 */
class FormManager
{
    
    /**
     * Translator.
     * @var Zend\I18n\Translator\Translator
     */
    private $translator;
    
    /**
     * Constructs the service.
     */
    public function __construct($translator) 
    {
        $this->translator = $translator;
    }
    
    /**
     * This method returns menu items depending on whether user has logged in or not.
     */
    public function translateErrorMessages($form)
    {
        $elements  = $form->getMessages();
        // Elements is an array of elements. Each element has an array of messages.
        // First we traverse the elements.
        foreach($elements as $elementName=>$messageArray){

            // Now we have an array.  We will traverse this array. 
            // We will translate each message.

            foreach($messageArray as $messageName=>$untranslatedMessage){
                $translatedMessage = $this->translator->translate($untranslatedMessage);
                
                // Now se set the message to the translated one
                $messageArray[$messageName] = $translatedMessage;
            }

            $elements[$elementName] = $messageArray;
        }

        $form->setMessages($elements);
         
        return $form;
    }
}