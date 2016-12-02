<?php
if (!defined('_PS_VERSION_'))
  exit;
 
class MyModule extends Module
{
  public function __construct()
  {
    $this->name = 'mymodule';
    $this->tab = 'administration';
    $this->version = '1.0.0';
    $this->author = 'manureva';
    $this->need_instance = 0;
    $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_); 
    $this->bootstrap = true;
 
    parent::__construct();
 
    $this->displayName = $this->l('xxxxTest Module');
    $this->description = $this->l('Test module created for learing the basics');
 
    $this->confirmUninstall = $this->l('Are you sure you want to uninstall? Aye');
 
    if (!Configuration::get('MYMODULE_NAME'))      
      $this->warning = $this->l('No name provided');
  
 
  }
  public function getContent()
{
    $output = null;
 
    if (Tools::isSubmit('submit'.$this->name))
    {
        $my_module_name = strval(Tools::getValue('MYMODULE_NAME'));
		$my_module_name2 = strval(Tools::getValue('MYMODULE_NAME1'));
        if (!$my_module_name
          || empty($my_module_name)
          || !Validate::isGenericName($my_module_name))
            $output .= $this->displayError($this->l('Invalid Configuration value'));
        else
        {
            Configuration::updateValue('MYMODULE_NAME', $my_module_name);
			Configuration::updateValue('MYMODULE_NAME1', $my_module_name2);
            $output .= $this->displayConfirmation($this->l('Settings updated'));
        }
    }
    return $output.$this->displayForm();
}

public function displayForm()
{
    // Get default language
    $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
     
    // Init Fields form array
    $fields_form[0]['form'] = array(
        'legend' => array(
            'title' => $this->l('Settings'),
        ),
        'input' => array(
            array(
                'type' => 'text',
                'label' => $this->l('Give An Input Sir:'),
                'name' => 'MYMODULE_NAME',
                'size' => 20,
                'required' => true
            ), array(
                'type' => 'text',
                'label' => $this->l('Give An Input Sir:'),
                'name' => 'MYMODULE_NAME1',
                'size' => 20,
                'required' => true
            )
        ),
        'submit' => array(
            'title' => $this->l('Save'),
            'class' => 'btn btn-default pull-right'
        )
    );
	
     
    $helper = new HelperForm();
     
    // Module, token and currentIndex
    $helper->module = $this;
    $helper->name_controller = $this->name;
    $helper->token = Tools::getAdminTokenLite('AdminModules');
    $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
     
    // Language
    $helper->default_form_language = $default_lang;
    $helper->allow_employee_form_lang = $default_lang;
     
    // Title and toolbar
    $helper->title = $this->displayName;
    $helper->show_toolbar = true;        // false -> remove toolbar
    $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
    $helper->submit_action = 'submit'.$this->name;
    $helper->toolbar_btn = array(
        'save' =>
        array(
            'desc' => $this->l('Save'),
            'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
            '&token='.Tools::getAdminTokenLite('AdminModules'),
        ),
        'back' => array(
            'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
            'desc' => $this->l('Back to list')
        )
    );
     
    // Load current value
    $helper->fields_value['MYMODULE_NAME'] = Configuration::get('MYMODULE_NAME');
    $helper->fields_value['MYMODULE_NAME1'] = Configuration::get('MYMODULE_NAME1'); 
    return $helper->generateForm($fields_form);
	
}
public function hookDisplayLeftColumn($params)
{
  $this->context->smarty->assign(
      array(
          'my_module_name' => Configuration::get('MYMODULE_NAME'),
          'my_module_link' => $this->context->link->getModuleLink('mymodule', 'display')
      )
  );
  return $this->display(__FILE__, 'mymodule.tpl');
}
   
public function hookDisplayRightColumn($params)
{
  return $this->hookDisplayLeftColumn($params);
}
   
public function hookDisplayHeader()
{
  $this->context->controller->addCSS($this->_path.'css/mymodule.css', 'all');
}   
  /*
  public function install()
{
  if (!parent::install())
    return false;
  return true;
}*/
public function install()
	{
		
								
		// Install Tabs
		$parent_tab = new Tab();
		// Need a foreach for the language
		$parent_tab->name[$this->context->language->id] = $this->l('Programme de fidelite Avance');
		$parent_tab->class_name = 'AdminControllerParentClassName';
		$parent_tab->id_parent = 0; // Home tab
		$parent_tab->module = $this->name;
		$parent_tab->add();
		
		
		$tab = new Tab();		
		// Need a foreach for the language
		$tab->name[$this->context->language->id] = $this->l('Configuration');
		$tab->class_name = 'AdminExample';
		$tab->id_parent = $parent_tab->id;
		$tab->module = $this->name;
		
		$tab->add();
		$tab = new Tab();		
		// Need a foreach for the language
		$tab->name[$this->context->language->id] = $this->l('Liste des points de fidelite');
		$tab->class_name = 'AdminExample';
		$tab->id_parent = $parent_tab->id;
		$tab->module = $this->name;
		$tab->add();
		
		$tab = new Tab();		
		// Need a foreach for the language
		$tab->name[$this->context->language->id] = $this->l('Ajout de points par utilisateur');
		$tab->class_name = 'AdminExample';
		//$tab->class_name = 'AdminExample';
		$tab->id_parent = $parent_tab->id;
		$tab->module = $this->name;
		$tab->add();
				
			 if (Shop::isFeatureActive())
    Shop::setContext(Shop::CONTEXT_ALL);
 
  return parent::install() &&
    $this->registerHook('leftColumn') &&
    $this->registerHook('header') &&
    Configuration::updateValue('MYMODULE_NAME', 'my friend');
   	
 } 
public function uninstall() {
        
        
        $tab = new Tab((int)Tab::getIdFromClassName('AdminExample'));
        $tab->delete();
        $tabMain = new Tab((int)Tab::getIdFromClassName('AdminControllerParentClassName'));
        $tabMain->delete();
        
        // Uninstall Module
        if (!parent::uninstall())
            return false;
            
        
        return true;
    }
}