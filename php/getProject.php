<?php
	include('auth.php');

	include_once "markdown.php";
    

function getColumn($content, $align) {
		echo '<div class="content">'; 
		echo Markdown($content);
		echo '</div>';
		echo '<div class="actions">';
		echo '<div class="edit" data-target="'.$align.'"></div>';
		echo '<div class="delete"></div>';		
		echo '</div>';
}


if(!empty($activeProject)){ 


	include_once('lib/db.php');

	$v1 = "'" . $conn->real_escape_string( $activeProject ) . "'";

	$sql='SELECT * FROM nodes, projects WHERE nodes.projects_id = projects.id AND projects.id = '.$v1.' ORDER BY nodes.edited DESC ';
	 
	$rs=$conn->query($sql);
	 
	if($rs === false) {
	 	trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $conn->error, E_USER_ERROR);
	} else {
	 	$rows_returned = $rs->num_rows;
	}



	?>
    <input id="pup" class="knob" data-width="120" data-angleOffset="0" data-fgColor="#C0ffff" data-skin="tron" data-thickness=".1" value="0">
	
	<div id="newMarkdown" class="row">

		<div class="c3 edit-mode" 'style=visibility:hidden;'>
			<p class="date">preview</p>
			<div class="content"></div>
			<div class="actions">
				<div class="edit" data-target="'.$align.'"></div>
				<div class="delete"></div>
			</div>
		</div>
		<div class="c3edit" style="display:inline-block;">
			<textarea class="editareafield" placeholder="start writing markdown here"></textarea>
			<div class="addPost"></div>
			<div class="backup"></div>
		</div>

	</div>

	<?
	$rs->data_seek(0);
	while( $node = $rs->fetch_assoc() ){

		$datum = $node['edited'];

	?>

		<div class="row">

			<div class="itemId"><? echo $node['id'] ?></div>
						

			<? if( $node['type'] == "txt") { ?>
	
			<div class="c3" 'style=visibility:hidden;'>
			<p class="date"><? echo $datum; ?></p>
			<?php  getColumn($node['content'], r);  ?>&nbsp;</div>
			<div class="c3edit"><textarea class="editareafield"></textarea>
			<div class="save"></div><div class="discardUpdate"></div>
			<div class="backup"></div>
			</div>
	
			<? } else if( $node['type'] == "img"){ ?>
			<div class="c3" >
			<p class="date"><? echo $datum; ?></p>
			<?  echo "<img src='projects/".$node['name']."/".$node['content']."'/>";  ?>
				<div class="actions image">
					<div class="delete"></div>		
				</div>

			</div>
			<div class="c3edit"><span class="save">save</span><br /><textarea class="editareafield"></textarea></div>
	
			<? } ?>
		</div>
		
		<?php
	}
}

?>