<?php
	namespace Project\Controllers;
	use \Core\Controller;

	class HelloController extends Controller {

		public function index() {
			$this->title = 'All done!';

			return $this->render('hello/index');
		}
	}
