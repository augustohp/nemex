<?php
	include(NEMEX_PATH.'auth.php');

	class user {
	
		var $user_id = '';
		var $active_project = '';
		var $projects = array();		


		function __construct( $uid) {
       		$this->user_id = $uid;
       		$this->loadProjects();
   		}


		function loadProjects() {
			$plist = array();

			if ($handle = opendir(NEMEX_PATH.'projects')) {
			    $blacklist = array('.', '..');
			    while (false !== ($file = readdir($handle))) 
			        if (!in_array($file, $blacklist)) 
			        	 $plist[filemtime(NEMEX_PATH.'projects/'.$file)] = $file;

			ksort($plist);
			$plist = array_reverse($plist);

			foreach ($plist as $p)
				array_push($this->projects, new project( $p, $this->user_id ) );
		
			closedir($handle);			    
			}
		}


		function getProjects() {
			return $this->projects;
		}


		function showProjects() {	
			
			if(sizeof($this->projects) > 0) {		
				foreach ($this->projects as $project)
					echo "<a href='index.php?view=".$project->getName()."'>
							<div class='project-list-item' style='background:linear-gradient(rgba(0, 0, 0, 0.45), rgba(0, 0, 0, 0.45)), url(projects/".$project->getTitleImage().") no-repeat left center; background-size: 100% auto ; '>
								<div class='item-info'>".$project->getName()."<br /> <span class='node-amount'>".$project->getNumNodes()." nodes</span></div>
								
								<div class='p_actions'>
									<div class='p_download'></div>
									<div class='p_delete'></div>
								</div>	
							</div>
						</a>";		
			}
			else echo "<div class='content'><h1>Hello World!</h1>
			<p>Here you can create new projects and collections.<br/>Inside of a project you can write, edit and delete texts<br/> or drag and drop some images to upload them.</p></div>";
		}


		function addProject($ptitle) {
			if(!empty($ptitle)){ 
				if (!file_exists(NEMEX_PATH.'projects/'.$ptitle)) {
				    mkdir(NEMEX_PATH.'projects/'.$ptitle, 0777, true);
				 	mkdir(NEMEX_PATH.'projects/'.$ptitle.'/big', 0777, true); 
				 	array_push($this->projects, new project( $ptitle, $this->user_id ) );
				}
				else echo "no";
			}else echo "error";
		}

	}

?>