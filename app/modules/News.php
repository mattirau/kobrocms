<?php
/**
 * News module be about dem news showings.
 * 
 * @author Lalitchandra Pakalomattam
 * @author Devadutt Chattopadhyay
 *
 */
class Module_News extends Module
{

	private function _newsQuery($pageId, $limit)
	{
		$limit = (int) $limit;
		
		// Be private method so no can call from module! Safe!
		
		$sql = "SELECT * FROM news WHERE page_id = :page_id ORDER BY created DESC LIMIT :limit";
                
                $stmt = $this->kobros->db->prepare($sql);
                
                $stmt->bindParam(":page_id", $pageId, PDO::PARAM_INT);
                
                $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
                
		$stmt->execute();
                
		$news = array();
		while($res = $stmt->fetch(PDO::FETCH_OBJ)) {
			$news[] = $res; 
		}
		
		
		return $news;

	}
	
	
	/**
	 * Headlines
	 * 
	 * @param $params
	 * @return string
	 */
	protected function _headlines($params)
	{
		$news = $this->_newsQuery($params['page'], $params['number']);
						
		$view = new View();
		$view->news = $news;
		$view->page_id = $params['page'];
		
		return $view->render(ROOT . '/app/templates/data/news/headlines.phtml');
				
	}	
	

	protected function _default($params)
	{
		$news = $this->_newsQuery($params['page'], 99999);
		$view = new View();
		$view->news = $news;
		$view->page_id = $params['page'];
		
		return $view->render(ROOT . '/app/templates/data/news/default.phtml');
				
	}	
	
	
	protected function _view($params)
	{
		$pageId = (int) $params['page'];
		$itemId = (int) $params['id'];
		
		$sql = "SELECT * FROM news WHERE page_id = ? AND id = ?";
		$stmt = $this->kobros->db->prepare($sql);
                $stmt->execute(array($pageId,$itemId));
                
                
		$news = array();
		while($res = $stmt->fetch(PDO::FETCH_OBJ)) {
			$news[] = $res; 
		}
		
		if(!sizeof($news)) {
			throw new Exception('No news be here');
		}
						
		$view = new View();
		$view->item = $news[0];
		
		$comments = array();
		$stmt = $this->kobros->db->prepare("SELECT * FROM news_comments WHERE news_id = ? ORDER BY created DESC");
                $stmt->execute(array($view->item->id));
                
                
		while($res = $stmt->fetch(PDO::FETCH_OBJ)) {
			$comments[] = $res;
		}
		
		$view->comments = $comments;
				
		return $view->render(ROOT . '/app/templates/data/news/view.phtml');
		
	}
	
	
	protected function _comment($params)
	{
				
		$pageId = (int) $params['page'];
		$itemId = (int) $params['id'];
		
		$sql = "SELECT * FROM news WHERE page_id = ? AND id = ?";
		$stmt = $this->kobros->db->prepare($sql);
                $stmt->execute(array($pageId,$itemId));
                
                
		$news = array();
		while($res = $stmt->fetch(PDO::FETCH_OBJ)) {
			$news[] = $res; 
		}
		
		if(!sizeof($news)) {
			throw new Exception('No news be here');
		}
		
		$item = $news[0];
		
		$now = new DateTime();
		$now = $now->format('Y-m-d H:i:s');
		
		$sql = "INSERT INTO news_comments (news_id, comment, created) VALUES(?, ?, ?)";
		$stmt = $this->kobros->db->prepare($sql);
		
		$stmt->execute(array($item->id, $_POST['comment'], $now));

		header("Location: {$_SERVER['HTTP_REFERER']}");
		
		
	}
	
	
	
}
