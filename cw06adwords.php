<?php
if (!defined('_CAN_LOAD_FILES_'))
	exit;


class cw06adwords extends Module {

    public function __construct($name = NULL) {
        $this->name = 'cw06adwords';
        $this->tab = 'analytics_stats';
        $this->version = '1.2';
        $this->author = 'creaweb06.fr';
        parent::__construct($name);
        $this->displayName = $this->l('Conversions Google Adwords');
        $this->description = $this->l('Ajoute le tag de conversion pour google adwords sur la page de confirmation de commande.');
    }

    public function install(){
        if(!parent::install()
                || !$this->registerHook('orderConfirmation'))
            return false;
        return true;
    }


    public function getContent(){

        $this->_html = '<h2>'.$this->displayName.'</h2>';

        if(Tools::isSubmit('submitConfig')){
            Configuration::updateValue('CW06ADWORDS_CONVERSION_ID',Tools::getValue('CONVERSION_ID'));
            Configuration::updateValue('CW06ADWORDS_CONVERSION_LABEL',Tools::getValue('CONVERSION_LABEL'));
            $this->_html .= $this->displayConfirmation('Paramètres enregistrés avec succès');
        }

        $this->_html .= '<fieldset>'
                . '<legend>'.$this->l('Configuration').'</legend>'
                . '<form method="POST">'
                . '<label>'.$this->l('Conversion ID').'</label>'
                . '<div class="margin-form">'
                . '<input type="text" name="CONVERSION_ID" value="'.Configuration::get('CW06ADWORDS_CONVERSION_ID').'" />'
                . '</div>'
                . '<label>'.$this->l('Conversion Label').'</label>'
                . '<div class="margin-form">'
                . '<input type="text" name="CONVERSION_LABEL" value="'.Configuration::get('CW06ADWORDS_CONVERSION_LABEL').'" />'
                . '</div>'
                . '<div class="margin-form">'
                . '<input type="submit" name="submitConfig" value="'.$this->l('Enregistrer').'" />'
                . '</div>'
                . '</form>'
                . '</fieldset>';

        return $this->_html;
    }





    public function hookOrderConfirmation($params){
        global $smarty;
        $smarty->assign('CONVERSION_ID',Configuration::get('CW06ADWORDS_CONVERSION_ID'));
        $smarty->assign('CONVERSION_LABEL',Configuration::get('CW06ADWORDS_CONVERSION_LABEL'));
        $smarty->assign('CONVERSION_AMOUNT',$params['objOrder']->total_paid);
        return $this->display(__FILE__, 'orderConfirmation.tpl');
    }

}

