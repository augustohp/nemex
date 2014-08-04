<?php

	include(NEMEX_PATH.'auth.php');

	include_once NEMEX_PATH."php/markdown.php";
	include_once NEMEX_PATH."php/node.php";

	class project {

		var $project_id = '';
		var $user_id = '';

		var $name = '';
		var $plainname = '';
		var $num_nodes = 0;

		var $nodes = array();


		function __construct( $pname, $uid ) {
       		$this->name = $pname;
       		$this->user_id = $uid;
   		}



   		function getId() {
   			return $this->project_id;
   		}

   		function getName(){
   			return $this->name;
   		}

   		function getNumNodes(){
   			$this->num_nodes = count(scandir(NEMEX_PATH.'projects/'.$this->name))-4;
   			return $this->num_nodes;
   		}


   		function showProject(){

   			echo '<div class="header"><span>'.$this->getName().'</span></div>';

   			echo '<div id="editmenu"></div>';	

			echo '<div class="navigation"><a class="index" href="index.php"><img src="img/nemex.svg" /></a>
										
										<a id="markdownhelp"><img src="img/markdown.svg" /></a>
			
			</div>';
		
echo '';

			echo '<progress id="uploadprogress" min="0" max="100" value="0" >0</progress><div id="project" class="pcontent">';
			
			echo '<article>';
			echo '<div id="holder">

			<input id="pup" class="knob" data-width="100" data-angleOffset="0" data-fgColor="#81DCDD" data-bgColor="#FFFFFF" data-thickness=".05" value="0"></div> ';
			echo '<p id="upload" class="hidden"><span class="cameraupload"></span><input id="uup" class="upload" type="file"></p>';
			echo '<p id="filereader"></p>';
			echo '<p id="formdata"></p>';
			echo '<p id="progress"></p>';
			echo '</article>';

			
			echo '<div class="activeProject">'.$this->getName().'</div>';
			echo '
		   <!-- <input id="pup" class="knob" data-width="120" data-angleOffset="0" data-fgColor="#C0ffff" data-skin="tron" data-thickness=".1" value="0">
			-->
			<div id="newMarkdown" class="row">
				<!--<div id="mobilephotoupload"></div>-->
				<div class="c3 edit-mode" \'style=visibility:hidden;\'>
					<p class="date">preview</p>
					<div class="ncontent"><div class="content"></div></div>
					<div class="actions">
						<div class="edit" data-target="'.$align.'"></div>
						<div class="delete"></div>
					</div>
				</div>
				<div class="c3edit" style="display:inline-block;">
					<textarea id="addfield" class="editareafield" placeholder="start writing markdown here" ></textarea>
					<div class="addPost"></div>
					<div class="discardAdd"></div>
					<div class="backup"></div>
				</div>

			</div>';
			$counter = 0;

			foreach ($this->nodes as $node) {
			
				$datum = $node->getDate(); 
				
				echo '<div class="row">

						<div class="snap-drawers">
							<div class="snap-drawer snap-drawer-right">
								<div class="edit m-sub e"></div><div class="delete m-sub d "></div>
						    </div>
						</div>

						<div  class="itemId">'.$node->getName().'</div>';
									

						if( $node->getType() == "md" || $node->getType() == "txt") {
							echo '<div id="p'.$counter.'" class="snap-content c3" \'style=visibility:hidden;\'>
								<p class="date">'.$datum.'</p>
								<div class="ncontent">';
						 	
						 	$this->getColumn($node->getContent(), r); 
						 	echo '</div></div>
								<div class="c3edit"><textarea class="editareafield"></textarea>
								<div class="save"></div><div class="discardUpdate"></div>
								<div class="backup"></div>';

							echo '</div>';
				
						} else if( $node->getType() == "img"){
							echo '
								<div id="p'.$counter.'" class="snap-content c3" >
									<p class="date">'.$datum.'</p>
									<div class="ncontent">';
									$big=file_exists(NEMEX_PATH."projects/".$this->name.'/big/'.$node->getName());
									if($big)
										echo '<a href="'.NEMEX_PATH."projects/".$this->name.'/big/'.$node->getName().'" target=_blank>';
									echo '<img src=\'projects/'.$this->name.'/'.$node->getName().' \'/>';
									if($big)
										echo '</a>';
										echo'
										<div class="actions image">
											<div class="download-big"></div>
											<div class="delete-big"></div>		
										</div>
									</div>
								</div>
								<div class="c3edit"><span class="save">save</span><br /><textarea class="editareafield"></textarea></div>';
						 } 
					echo '</div>';
					$counter++;
			}	
			echo '</div>';
			echo '<script> var noElements = '.$counter.';
			</script>';

   		}



		function getColumn($content, $align) {	
				//$content = substr($content, 17, strlen($content));
				echo '<div class="content">'; 
				echo Markdown($content);
				echo '</div>';
				echo '<div class="actions">';
				echo '<div class="edit-big" data-target="'.$align.'"></div>';
				echo '<div class="download-big"></div>';	
				echo '<div class="delete-big"></div>';		
				echo '</div>';
		}


		function addProject() {}

		function deleteProject() {
			foreach (new DirectoryIterator(NEMEX_PATH.'projects/'.$this->name.'/big') as $fileInfo) {
			    if($fileInfo->isDot() || !$fileInfo->isFile()) continue;
			    unlink(NEMEX_PATH.'projects/'.$this->name."/big/".$fileInfo->getFilename());
			}
			rmdir(NEMEX_PATH.'projects/'.$_GET['project'].'/big');

			foreach (new DirectoryIterator(NEMEX_PATH.'projects/'.$this->name) as $fileInfo) {
			    if($fileInfo->isDot() || !$fileInfo->isFile()) continue;
			    unlink(NEMEX_PATH.'projects/'.$this->name."/".$fileInfo->getFilename());
			}
			rmdir(NEMEX_PATH.'projects/'.$this->name);
		}


		function getNodes() {
			$files = array();
			
			$f = glob(NEMEX_PATH.'projects/'.$this->name.'/{*.jpg,*.gif,*.png,*.md,*.txt}', GLOB_BRACE);
			if (is_array($f) && count($f) > 0) {
				$files = $f;
			}

			sort($files);
			$files = array_reverse($files);


			$counter = 0;

			foreach ($files as $entry) {
				array_push($this->nodes, new node($entry, $this->name) );
			}

		}


		function getTitleImage() {
			$this->getNodes();
			
			foreach ($this->nodes as $node)
				 if($node->getType() == 'img')
					return $this->name.'/'.$node->getName();
		}


		function addNode() {}

		function delNode() {}

	}

?>