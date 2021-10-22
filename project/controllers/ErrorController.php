<?php
	namespace Project\Controllers;
	use \Core\Controller;
	
	class ErrorController extends Controller {

		public function notFound() {
			$this->title = 'Page is not found!';
			
			return $this->render('error/notFound');
		}
	}
