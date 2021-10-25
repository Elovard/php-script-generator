<?php
	namespace Project\Models;
	use \Core\ParticipantModel;
	
	class Page extends ParticipantModel {

		public function getById($id) {
			return $this->findOne("SELECT * FROM participants WHERE entity_id=$id");
		}
		
		public function getAll() {
			return $this->findMany("SELECT * FROM participants");
		}
	}
