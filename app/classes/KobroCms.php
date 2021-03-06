<?php
/**
 * This be the main KobroCRM klass.
 * 
 * @author Devadutt Chattopadhyay
 * @author Rajanigandha Balasubramanium
 * @author Lalitchandra Pakalomattam
 *   
 */
class KobroCms
{
	/**
	 * Config ini be parsed to array here.
	 * 
	 * @var array
	 */
	public $config;
	
	/**
	 * This be PDO reference
	 * 
	 * @var PDO 
	 */
	public $db;
	
	/**
	 * Page
	 * 
	 * @var stdClass
	 */
	public $page;
	
	/**
	 * View
	 * 
	 * @var View
	 */
	public $view;
		
	
	/**
	 * User
	 * 
	 * @var User
	 */
	public $user;
	
	private function __construct()
	{
		// We parse customers config.s
		$this->config = $config = parse_ini_file(ROOT . "/config.ini");
		
                // Define DOMAIN static variable for use in temlates
                define("DOMAIN", $this->config["domain"]);

		$this->db = new PDO("mysql:host={$this->config['db_host']};dbname={$this->config['db_schema']}", $this->config['db_user'], $this->config['db_password']);
		$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	
	
	
	/**
	 * Return instance of CMS
	 * 
	 * @return KobroCms
	 */
	public static function getInstance()
	{
		static $instance;
		if(!$instance) {
			$instance = new KobroCms();
		}
		
		return $instance;
	}
		
	
	/**
	 * Returns page
	 * 
	 * @param $pageId Page id
	 * @return stdClass
	 */
	public function getPage($pageId)
	{
		// A bit useless but 
		$pageId = (int) $pageId;
		
		$sql = "SELECT * FROM page WHERE id = ?";
                
                $stmt = $this->db->prepare($sql);
                $stmt->execute(array($pageId));
                
		return $stmt->fetch(PDO::FETCH_OBJ);
	}
	
	
	/**
	 * Executing a module command
	 * 
	 * @param $params array
	 * @return string Module return html always
	 */
	public function executeModule($params)
	{
		// Autoload module from modules directory intelligently.
		$module = 'Module_' . $params['module'];
		require_once ROOT . '/app/modules/' . $params['module'] . '.php';
		$module = new $module();
		
		// Delegating executable
		return $module->execute($params);
		
	}
	
	
	
	/**
	 * Main runner kobros
	 * 
	 * @return string Html response to give user
	 */
	public function run()
	{
		// Init user
		$this->user = User::getInstance();

		// Init view
		$this->view = new View();
		
		// Fetch page. If no page use start page.
		$pageId = (isset($_GET['page'])) ? $_GET['page'] : $this->config['page_default']; 
		$this->page = $page = $this->getPage($pageId);
		
		// If invalid page we throw exception
		if(!$this->page) {
			throw new Exception('Page not found');
		}
		
		// Render inner-template
		$this->view->innertpl = $this->view->render(ROOT . '/app/templates/inner/' . $page->innertpl . '.phtml');
		
		// If user request template we use it
		$tpl = (isset($_REQUEST['tpl'])) ? $_REQUEST['tpl'] : $page->tpl; 
		
		// HTML TITLE is always page titel.
		$this->view->title = $this->page->title;
		
		// If admin role we include the admin scripts.
		if($this->user->obj->role == 'admin') {
			$this->view->includeAdminScripts = true;
		} else {
			// No go.
			$this->view->includeAdminScripts = false;
		}
		
		// User can not go outside webroot so we fix the tpl param not to has goto up directory
		$tpl = str_ireplace('../', '', $tpl);
		
		// We render outer template, inject inner teplate to it
		return $this->view->render(ROOT . '/app/templates/outer/' . $tpl . '.phtml');
				
		// All is good.
		
	}

	
	
	
	
	
	
	
	
	
	
	
}