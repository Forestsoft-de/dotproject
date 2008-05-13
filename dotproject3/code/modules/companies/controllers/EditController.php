<?php
/**
 * Controller for company object editing functions.
 * 
 * @package dotproject
 * @subpackage companies
 * @version 3.0 alpha
 *
 */
class Companies_EditController extends DP_Controller_Action {
	
	/**
     * FlashMessenger
     *
     * @var Zend_Controller_Action_Helper_FlashMessenger
     */
    protected $_flashMessenger = null;
	
    public function init() {
    	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    }
    
    
	/**
	 * Create a new company object
	 */
	public function newAction() {
		//$title_block = $this->_helper->TitleBlock('New Company', '/img/_icons/companies/handshake.png');
		$title_block = $this->_helper->TitleBlock('');
		$title_block->addCrumb('/companies', 'companies');	
		$title_block->addCrumb('/companies/edit/new', 'new company');
		$this->view->company_name = 'New company';
		$this->_helper->actionStack('object', 'edit', 'companies');
	}
	
	/**
	 * Modify an existing company object
	 */
	public function objectAction() {
		Zend_Loader::registerAutoload();
		
		$company_id = $this->getRequest()->id;


		$form_definition = $this->_helper->FormDefinition('edit-object');
		$edit_form = new Zend_Form($form_definition);
		
		$types = DP_Config::getSysVal( 'CompanyType' );
		$company_type_element = $edit_form->getElement('company_type');
		$company_type_element->addMultiOptions($types);
		
		if ($company_id) {
			// TODO - set default adapter pre dispatch
			$db = DP_Config::getDB();
			Zend_Db_Table_Abstract::setDefaultAdapter($db);

			$companies = new Db_Table_Companies();
			$rows = $companies->find($company_id);
			$obj = $rows->current();
			$obj_hash = $obj->toArray();
			
			$edit_form->setDefaults($obj_hash);

			if (!$this->view->company_name) {
				$this->view->company_name = $obj_hash['company_name'];
			}
			
			if (!$this->view->titleblock) {
				//$title_block = $this->_helper->TitleBlock('Edit Company', '/img/_icons/companies/handshake.png');
				$title_block = $this->_helper->TitleBlock('');
				$title_block->addCrumb('/companies', 'companies');
				$title_block->addCrumb('/companies/view/object/id/'.$company_id, $obj_hash['company_name']);
				$title_block->addCrumb('/companies/edit/object/id/'.$company_id, 'edit');
			}
				
		} else {
			// no id supplied, error
		}
		
		$this->view->form = $edit_form;
	}
	
	/**
	 * Save a company object
	 */
	public function saveAction() {
		$this->_helper->viewRenderer('object');
		Zend_Loader::registerAutoload();
		
		// Retrieve the form definition from views/forms
		$form_definition = $this->_helper->FormDefinition('edit-object');
		$edit_form = new Zend_Form($form_definition);
		
		if (!$edit_form->isValid($_POST)) {
			$this->view->form = $edit_form;		
		} else {
			// form is ok
			$this->_helper->viewRenderer->setNoRender();
			//$company = Company::bind($_POST);
			
			$db = DP_Config::getDB();
			Zend_Db_Table_Abstract::setDefaultAdapter($db);
			
			$companies = new Db_Table_Companies();
			$company_id = $_POST['company_id'];
			
			if ($company_id != '') {
				$where = $companies->getAdapter()->quoteInto('company_id = ?', $company_id);
				$updated_company = $companies->createRow($_POST);
				$companies->update($updated_company->toArray(), $where);
			} else {
				$new_company = $companies->createRow($_POST);
				// Empty primary key throws an exception, so force null value.
				$new_company->company_id = null;
				$new_company->save();
			}
			
			// Display a nice message which confirms the save, and views the object
			// TODO - Flashmessenger requires Zend_Session to work correctly.
			$this->_flashMessenger->addMessage('Company saved.');
			
			$this->_redirector = $this->_helper->getHelper('Redirector');
			$this->_redirector->setGoto('index', 'index', 'companies');
            $this->_redirector->redirectAndExit();             
		}
	}
}
?>