<?php
/* ----------------------------------------------------------------------
 * app/plugins/ULAN/controllers/ImportController.php :
 * ----------------------------------------------------------------------
 * CollectiveAccess
 * Open-source collections management software
 * ----------------------------------------------------------------------
 *
 * Software by Whirl-i-Gig (http://www.whirl-i-gig.com)
 * Copyright 2015 Whirl-i-Gig
 *
 * For more information visit http://www.CollectiveAccess.org
 *
 * This program is free software; you may redistribute it and/or modify it under
 * the terms of the provided license as published by Whirl-i-Gig
 *
 * CollectiveAccess is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTIES whatsoever, including any implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * This source code is free and modifiable under the terms of
 * GNU General Public License. (http://www.gnu.org/copyleft/gpl.html). See
 * the "license.txt" file for details, or visit the CollectiveAccess web site at
 * http://www.CollectiveAccess.org
 *
 * ----------------------------------------------------------------------
 */

require_once(__CA_MODELS_DIR__.'/ca_entities.php');
require_once(__CA_MODELS_DIR__.'/ca_occurrences.php');
require_once(__CA_LIB_DIR__.'/core/Search/SearchEngine.php');
require_once(__CA_LIB_DIR__.'/ca/Search/EntitySearch.php');
require_once(__CA_LIB_DIR__.'/ca/Search/OccurrenceSearch.php');


class ImportController extends ActionController {
	# -------------------------------------------------------
	/**
	 *
	 */
	protected $opo_config;		// plugin configuration file

	# -------------------------------------------------------
	# Constructor
	# -------------------------------------------------------
	/**
	 *
	 */
	public function __construct(&$po_request, &$po_response, $pa_view_paths=null) {
		// Set view path for plugin views directory
		$name = "madeMACUSP"; 

		if (!is_array($pa_view_paths)) { $pa_view_paths = array(); }
		$pa_view_paths[] = __CA_APP_DIR__."/plugins/$name/themes/views/";

		// Load plugin configuration file
		$this->opo_config = Configuration::load(__CA_APP_DIR__."/plugins/$name/conf/config.conf");

		parent::__construct($po_request, $po_response, $pa_view_paths);

		/*if (!$this->request->user->canDoAction('can_import_ulan')) {
			$this->response->setRedirect($this->request->config->get('error_display_url').'/n/3000?r='.urlencode($this->request->getFullUrlPath()));
			return;
		}

		// Load plugin stylesheet*/
		//MetaTagManager::addLink('stylesheet', __CA_URL_ROOT__."/app/plugins/consulthor/themes/themes/css/consulthor.css",'text/css');
		
	}
	# -------------------------------------------------------
	/**
	 *
	 */
	public function Index() 
	{	
		$change = $this->opo_config->get('change'); 
		$type = $change["type"]; 

		$o_search = new OccurrenceSearch();
		
		$o_items = $o_search->search("type:$type");

		$this->view->setVar('items', $o_items);			
		$this->view->setVar('config', $this->opo_config->get('change'));	
		$this->view->setVar('type', ca_lists::getItemID('occurrence_types', $type));	

		$this->render("roboto.php");
	}
	public function changeType() {		
		/*$o_search = new EntitySearch();
		$qr_result = $o_search->search('*');
		*/
		$id = $this->request->getParameter('idno', pString); 		
		$o_entity = new ca_occurrences($id);
		$o_entity->setMode(ACCESS_WRITE);	

		$change = $this->opo_config->get('change');	
		$prev_Parameter = $change["prevParameter"]; 
		$next_Parameter = $change["nextParameter"]; 
		$values = $change["values"]; 

		$type = $o_entity ->get("ca_occurrences.$prev_Parameter");

		//$o_entity->removeAttribute($next_Parameter);			
		$string = ""; 

		foreach ($values as $key => $value) {
			$prev = ca_lists::getItemIDLabel($prev_Parameter, $value["prev"]); 
			$next = ca_lists::getItemIDLabel($next_Parameter, $value["next"]); 
			
			if ($prev == $type)
			{
    			$o_entity->replaceAttribute(array($next_Parameter => $next), $next_Parameter);	
				$o_entity->update(); 
			}
		}

		$o_entity2 = new ca_occurrences($id);
		
		$data = array(); 
		//$data["results"] = " $ ". $o_entity2->getWithTemplate("^ca_occurrences.$next_Parameter");		
		$data["results"] = " -> ". $code;		
		$this->view->setVar('results', $data);		
		$this->render("jsonresult.php");
	}
	public function changeTest() {		
		/*$o_search = new EntitySearch();
		$qr_result = $o_search->search('*');
		*/
		#$id = $this->request->getParameter('idno', pString); 				

		#$o_entity2 = new ca_occurrences($id);
		#$data = array(); 

		#$data["results"] = " -> ". $o_entity2->getWithTemplate("^ca_occurrences.madeMACUSP");		
		$data["results"] = " -> Hola";		
		$this->view->setVar('results', $data);		
		$this->render("jsonresult.php");
	}
	public function test() 
	{	
		$this->view->setVar('config', $this->opo_config->get('change'));		
		$this->render("test2.php");
	}
}
