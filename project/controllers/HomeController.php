<?php
	namespace Project\Controllers;
	use \Core\Controller;

	class HomeController extends Controller {

		public function index() {
			$this->title = 'All done!';

			return $this->render('hello/index');
		}
	}
