<?php
require_once($CFG->dirroot.'/lib/coursecatlib.php');
function get_first_categorie_to_create(){ 
   $categories = coursecat::get(0)->get_children(array('sort' => array('id' => 1)));
	        
   foreach ($categories as $category) {
        if (has_capability('moodle/course:create', $category->get_context())){
		   if($category->id != 1) {
		      return $category->id;
		   } else {
		      $categoryInterId = $category->id;
		   }
	    }   
    }           
	return $categoryInterId;
}   

function local_myplugin_extend_navigation(global_navigation $navigation) {
/* Si on veut supprimer l'item "Accueil du site" du bloc navigation : */
	if ($home = $navigation->find('home', global_navigation::TYPE_SETTING)) {
	        $home->remove();
	}
/* Faire en sorte que le lien "Cours actuel" soit fermé à l'arrivée sur un cours Moodle, afin d'alléger l'affichage du bloc "Navigation" */
	if ($currentcourse = $navigation->find('currentcourse', global_navigation::TYPE_ROOTNODE)) {
		$currentcourse->forceopen = false;
	}
        if ($mycourses = $navigation->find('mycourses', global_navigation::TYPE_ROOTNODE)) {
                $mycourses->forceopen = false;
        }
        if ($courses = $navigation->find('courses', global_navigation::TYPE_ROOTNODE)) {
                $courses->forceopen = false;
        }
	if(user_can_create_courses()){
	$idCategorie = 1;
	$idCategorie = get_first_categorie_to_create();
	$nodeAction = $navigation->add(get_string('Actions', 'local_myplugin'));
	$nodeCreateCourse = $nodeAction->add(get_string('Create_course', 'local_myplugin'), new moodle_url('/course/edit.php', array('category'=>$idCategorie)));
	/*	ATTENTION, 'view'=>'courses' pas fonctionnel car le changement de page ramène à la vue catégorie... De plus il faut mettre en premier les cours ou l'in est prof ou propriétaire... 
	$nodeManageCourse = $nodeAction->add(get_string('Manage_courses'), new moodle_url('/course/management.php', array('categoryid'=>$idCategorie,'view'=>'courses'))); */
	$nodeManageCourse = $nodeAction->add(get_string('Manage_courses', 'local_myplugin'), new moodle_url('/course/management.php', array('categoryid'=>$idCategorie)));
	$nodeAction->force_open();
	}
}
?>
