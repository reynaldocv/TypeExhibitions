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
		$name = "TypeExhibitions"; 

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
		$o_search = new OccurrenceSearch();
		$o_items = $o_search->search("*");
		$this->view->setVar('items', $o_items);		
		$this->render("roboto.php");
	}

	public function Lista() {
		$o_search = new EntitySearch();
		$o_items = $o_search->search("*");

		$this->view->setVar('items', $o_items);
		
		$this->render("lista.php");
	}
	
	
	public function ShowProfile() {		
		$id = $this->request->getParameter('idno', pString); 		

		$o_entity = new ca_entities($id);

		$this->view->setVar('idno', $id);
		$this->view->setVar('entity', $o_entity);			
		
		$this->render("profile.php");
	}

	public function ShowProfileWikidata() {
		//$obj = new ca_objects('123');
		//$obj-->setMode(ACCESS_WRITE);
		//$abc = $obj-->replaceAttribute(array('attr'=->'NewValue'), 'attr');
		//$obj-->update();

		//$AUTH_CURRENT_USER_ID = 1; 
		$id = $this->request->getParameter('idno', pString); 		

		$o_entity = new ca_entities($id);

		//$o_entity->setMode(ACCESS_WRITE);	

		$this->view->setVar('idno', $id);
		$this->view->setVar('entity', $o_entity);			
		
		$this->render("profilewiki.php");
	}

	public function ModifyBiography() {		
		$o_search = new EntitySearch();
		$qr_result = $o_search->search('*');

		$id = $this->request->getParameter('idno', pString); 
		$newBio = $this->request->getParameter('newBiography', pString); 

		$o_entity = new ca_entities($id);

		//$o_entity->setMode(ACCESS_WRITE);		
		
		$o_entity->replaceAttribute(array('biography' => $newBio),'biography');	
		$o_entity->update();

		$o_entity = new ca_entities($id);

		$this->view->setVar('idno', $id."-".$newBio);
		$this->view->setVar('entity', $o_entity);
		$this->view->setVar('label', $o_entity->get("ca_entities.preferred_labels"));
		
		$this->view->setVar('list', $qr_result);
		
		$this->render("profile.php");
	}

	public function changeType() {		
		/*$o_search = new EntitySearch();
		$qr_result = $o_search->search('*');

		*/
		$id = $this->request->getParameter('idno', pString); 
		$o_entity = new ca_occurrences($id);

		//$o_entity->setMode(ACCESS_WRITE);		
		$type = $o_entity ->getWithTemplate('^ca_occurrences.tipo_exposicao', array('locale' => 'en_US'));
		
		if ($type == "Exposição produzida pelo MAC USP")
		{
			$o_entity->replaceAttribute(array('madeMACUSP' => 'Exposição produzida pelo MAC USP'),'madeMACUSP');	
		}
		if ($type == "Exhibition previous to MAC USP acquisition")
		{	$o_entity->replaceAttribute(array('madeMACUSP' => 'Exhibitions from other institutions'),'madeMACUSP');	
			
		}
		if ($type == "Exhibition in other institution (loan)")
		{
			$o_entity->replaceAttribute(array('madeMACUSP' => 'Exhibitions from other institutions'),'madeMACUSP');	
		}
		$o_entity->update(); 

		$o_entity2 = new ca_occurrences($id);

		$data = array(); 

		$data["results"] = $type." - ". $o_entity2->getWithTemplate("^ca_occurrences.madeMACUSP");
		
		$this->view->setVar('results', $data);
		
		$this->render("jsonresult.php");
	}


	public function QueryToWikidata(){

		$query = $this->request->getParameter('consulta', pString);

		if (trim($query) == "") 
			$query = "Leornado da vinci";
		
		$query = str_replace(" ","%20", $query);
		// Entity to look up (e.g., Q42 is the Wikidata ID for Douglas Adams)
		//$itemId = 'Q42';

		// Construct the API URL
		//$url = "https://www.wikidata.org/w/api.php?action=wbgetentities&ids=$itemId&format=json";

		$url = "https://www.wikidata.org/w/api.php?action=wbsearchentities&format=json&language=en&search=$query";

		// Set headers for the request
		$options = [
			"http" => [
				"header" => "User-Agent: PHP Wikidata Example"
			]
		];
		$context = stream_context_create($options);

		// Make the HTTP request
		$response = file_get_contents($url, false, $context);

		// Decode the JSON response
		$data = json_decode($response, true);

		//$array = array("results" => $data); 

		$this->view->setVar('results', $data);
		
		$this->render("jsonresult.php");
	}



	public function QueryToULAN(){

		$_searchText = $this->request->getParameter('consulta', pString);

		if (trim($_searchText) == "") 
			$_searchText = "Rafael Sanzio";
		
		$query = "select ?Subject ?name ?Term ?Parents ?bio {
			?Subject a skos:Concept; luc:term '$_searchText'; skos:inScheme ulan: ;
			gvp:prefLabelGVP [xl:literalForm ?Term].		  
			optional {?Subject gvp:parentStringAbbrev ?Parents}				
			optional {?Subject foaf:focus [gvp:biographyPreferred [schema:description ?bio]]}
			}";

		/*$query = "select ?x ?name ?bio ?nationality ?type {
			?x gvp:broaderExtended ulan:500000002. # Persons, Artists
			optional {?x gvp:agentTypePreferred [gvp:prefLabelGVP [xl:literalForm ?type]]}
			optional {?x foaf:focus [gvp:nationalityPreferred [gvp:prefLabelGVP [xl:literalForm ?nationality]]]}
			optional {?x gvp:prefLabelGVP [xl:literalForm ?name]}
			optional {?x foaf:focus [gvp:biographyPreferred [schema:description ?bio]]}
			}";*/


		$url = 'https://vocab.getty.edu/sparql?query='.urlencode($query) .'&format=json';
		

		//$ch= curl_init();
		
		//curl_setopt($ch, CURLOPT_URL, $url);
		//curl_setopt($ch, CURLOPT_USERAGENT, "Google Chrome Browser");
		//curl_setopt($ch, CURLOPT_HEADER, "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8");

		//$data = curl_exec($ch);

		//curl_close($ch);

		$options = [
			"http" => [
				"header" => "Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8". 
							"User-agent: Google Chrome Browser"			
			]
		];

		$context = stream_context_create($options);

		// Make the HTTP request
		$response = file_get_contents($url, false, $context);

		// Decode the JSON response
		$data = json_decode($response, true);

		$this->view->setVar('results', $data["results"]["bindings"]);
		
		$this->render("jsonresult.php");
	}

	public function WikipediaLinks(){

		$query = $this->request->getParameter('consulta', pString);
		
		if (trim($query) == "") 
			$query = "Q5588";
		
		$query = str_replace(" ","%20", $query);
		// Entity to look up (e.g., Q42 is the Wikidata ID for Douglas Adams)
		//$itemId = 'Q42';

		// Construct the API URL
		//$url = "https://www.wikidata.org/w/api.php?action=wbgetentities&ids=$itemId&format=json";
		//https://www.wikidata.org/wiki/Special:EntityData/Q61965584.json

		//$url = "https://www.wikidata.org/w/api.php?action=wbsearchentities&format=json&language=en&search=$query";
		$url = "https://www.wikidata.org/wiki/Special:EntityData/$query.json";

		// Set headers for the request
		$options = [
			"http" => [
				"header" => "User-Agent: PHP Wikidata Example"
			]
		];
		$context = stream_context_create($options);

		// Make the HTTP request
		$response = file_get_contents($url, false, $context);

		// Decode the JSON response
		$data = json_decode($response, true);

		//$array = array("results" => $data); 

		$this->view->setVar('results', $data["entities"][$query]["sitelinks"]);
		
		$this->render("jsonresult.php");
	}

	public function SaveLinks()
	{	
		try {
			//$query = $this->request->getParameter('consulta', pString);
			//$link = $this->request->getParameter('wikibioText', pString);
			
			$o_item= new ca_entities("1");

			//$o_entity->setMode(ACCESS_WRITE);		
			
			//$attributes = array();

			//$attributes = $o_item->getParameter("webpages");

			//$attributes[] = "Hola.com";

			//$o_entity->replaceAttribute(array('wikibio' =>'https://www.google.com/'),'IDcodes');
			//$o_entity->replaceAttribute(array('ulancode' =>'ulan'),'IDcodes');
			//$o_entity->replaceAttribute(array('wikicode' =>'wiki'),'wikicode');
			
			//$attributes["wikibio"] = "https://www.google.com/";
			//$attributes["wikicode"] = "wiki";
			//$attributes["ulancode"] = "ulan";
			
			//$o_item->getAttribute($arr, 'webpages');
			
			//$o_item->replaceAttribute(array('wikicode' => "jaja1"), 'IDcodes');
			//$o_item->replaceAttribute(array('ulancode' => "jaja2"), 'IDcodes');
			//$o_item->replaceAttribute(array('wikicode' => "jaja1", 'ulancode' => "jaja2"), 'IDcodes');
			//$o_item->replaceAttribute(array('wikicode' => "jaja1"), 'IDcodes.wikicode');
			//$o_item->replaceAttribute(array('ulancode' => "jaja1"), 'IDcodes.ulancode');
			//$arr = getAttribute('IDcodes');
			//$arr["ulancode"] = "ulan"; 
			//$arr["wikicode"] = "wiki"; 


			//$o_item->replaceAttribute($arr, 'IDcodes');

			$o_item->addAttribute(array('wikicode' => "wiki"), 'IDcodes');

			$o_item->addAttribute(array('url'=>"fin.com"), 'webpages'); 
		
			$o_item->update();
			
			$array = array("results" => $arr);			
			$this->view->setVar('results', $array);
			$this->render("jsonresult.php");
			
		
		}
		 catch (Exception $e) {
			$array = array("results" => "Error!!!");			
			$this->view->setVar('results', $array);
			$this->render("jsonresult.php");
		}

		
	}	
	public function SaveData()
	{	
		try {
			$option = $this->request->getParameter('option', pString);
			$idno = $this->request->getParameter('idno', pString);

			$_code 		= $this->request->getParameter('_code', pString);			
			$_url  		= $this->request->getParameter('_url', pString);
			$_comment 	= $this->request->getParameter('_comment', pString);

			//$query = $this->request->getParameter('consulta', pString);
			//$link = $this->request->getParameter('wikibioText', pString);
			
			$o_item= new ca_entities($idno);

			//$o_item->addAttribute(array('url'=>$value), 'webpages');

			if ($type == "link")			
			{	
				$link = array("url_source" => $label, "url_entry" => $value);
				$o_item->addAttribute($link, 'external_link');	
					
				$o_item->setMode(ACCESS_WRITE);			
				$o_item->update();

				$ans = "The following data <br>"; 
				$ans .= " <b>link </b>.$value"; 
				$ans .= " <br>was saved! <br>"; 
				
			
			}
			else
			{
				$link = array("wikicode" => $label, "ulancode" => $value);
				$o_item->replaceAttribute($link, 'IDcodes');	
				
				$o_item->setMode(ACCESS_WRITE);			
				$o_item->update();

				$ans = "The following data <br>"; 
				$ans .= " <b>IDcodes </b>".$o_item->get("IDcodes"); 
				$ans .= " <br>was saved! <br>"; 
			
			}
			
			$array = array("results" => $ans);		

			$this->view->setVar('results', $array);
			$this->render("jsonresult.php");
			
		
		}
		 catch (Exception $e) {
			$array = array("results" => "Error!!!");			
			$this->view->setVar('results', $array);
			$this->render("jsonresult.php");
		}

		
	}	
	public function SaveCodes()
	{	
		try {
			$option = $this->request->getParameter('option', pString);
			$idno = $this->request->getParameter('idno', pString);

			$_code 		= $this->request->getParameter('_code', pString);			
			$_url  		= $this->request->getParameter('_url', pString);
			$_comment 	= $this->request->getParameter('_comment', pString);
			
			$o_item= new ca_entities($idno);

			if ($option == "WIKI")
			{	
				$newData = array("wikicode" => $_code, "wikiurl" => $_url, "wikicomment" => $_comment);
				$o_item->replaceAttribute($newData, 'wikidata');	
			
			}
			if ($option == "ULAN")
			{
				$newData = array("ulancode" => $_code, "ulanurl" => $_url, "ulancomment" => $_comment);
				$o_item->replaceAttribute($newData, 'ulan');	
			}	

			$o_item->setMode(ACCESS_WRITE);			
			$o_item->update();

			$_results = "No code saved!";
			$_status = "No code saved!";

			if ($option == "WIKI")
			{	
				$_code = $o_item->get("ca_entities.wikidata.wikicode");
				$_url = $o_item->get("ca_entities.wikidata.wikiurl");

				$_results = "<a href='$_url' target='blank'>$_code</a>";
				$_status = $o_item->get("ca_entities.wikidata.wikicomment");
					
			}
			if ($option == "ULAN")
			{
				$_code = $o_item->get("ca_entities.ulan.ulancode");
				$_url = $o_item->get("ca_entities.ulan.ulanurl");

				$_results = "<a href='$_url' target='blank'>$_code</a>";
				$_status = $o_item->get("ca_entities.ulan.ulancomment");
			}
			
			$array = array("results" => $_results, "status" => $_status);		
			$this->view->setVar('results', $array);

			$this->render("jsonresult.php");
			
		
		}
		 catch (Exception $e) {
			$array = array("results" => "Error!!!");			
			$this->view->setVar('results', $array);
			$this->render("jsonresult.php");
		}

		
	}	
	public function Test()
	{	
		try {
			//$o_item= new ca_entities(1);

			//$o_item->addAttribute(array('url'=>$value), 'webpages');

			//$ulancode = $o_item->getAttribute("IDcodes");

			$ulancode = "This is providence!!!";
			
			$array = array("results" => $ulancode);		

			$this->view->setVar('results', $array);
			$this->render("jsonresult.php");
			
		
		}
		catch (Exception $e) {
			$array = array("results" => "Error!!!");			
			$this->view->setVar('results', $array);
			$this->render("jsonresult.php");
		}

		
	}	

}
