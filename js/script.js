jQuery.fn.visible = function() {
 return this.css('visibility', 'visible');
};

jQuery.fn.invisible = function() {
 return this.css('visibility', 'hidden');
};

jQuery.fn.visibilityToggle = function() {
 return this.css('visibility', function(i, visibility) {
   return (visibility == 'visible') ? 'hidden' : 'visible';
 });
};





$( window ).load(function() {

  for(var i = 0; i < noElements; i++){
  
    if($('#p'+i).children('.ncontent').children('img').length || $('#p'+i).children('.ncontent').children('a').children('img').length) {
      //alert("img");
      $('#p'+i).parent().children('.snap-drawers').children('.snap-drawer').children('.e').hide();
      $('#p'+i).parent().children('.snap-drawers').children('.snap-drawer').children('.m-sub').height( $('#p'+i).children('.ncontent').height()-73);
    } 
    else
      $('#p'+i).parent().children('.snap-drawers').children('.snap-drawer').children('.m-sub').height( ($('#p'+i).children('.ncontent').height()) /2);
    }

});




$( window ).resize(function() {

  for(var i = 0; i < noElements; i++){
  
    if($('#p'+i).children('.ncontent').children('img').length || $('#p'+i).children('.ncontent').children('a').children('img').length) {
      $('#p'+i).parent().children('.snap-drawers').children('.snap-drawer').children('.e').hide();
      $('#p'+i).parent().children('.snap-drawers').children('.snap-drawer').children('.m-sub').height( $('#p'+i).children('.ncontent').height()-73);
    } 
    else
      $('#p'+i).parent().children('.snap-drawers').children('.snap-drawer').children('.m-sub').height( ($('#p'+i).children('.ncontent').height()) /2);
  }

});




$(document).ready(function(){



  var mobile = false;
  if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
    mobile = true;


    var snappers = new Array();
    for(var i = 0; i < noElements; i++){
      snappers.push( 
        new Snap({
            element: document.getElementById('p'+i),
            disable:'left',
            minPosition: -55,
           })
      );
    }
  }

  $('textarea').autosize();

  if(!mobile ){
    $(".row").hover(
      function(){ if($(this).children('.c3edit').is(':hidden') ) $(this).children('.c3').find('.actions').css('visibility', 'visible'); },
      function(){ $(this).children('.c3').find('.actions').css('visibility', 'hidden'); }
    );
  }

  
  $(".project-list-item").hover(function(){
    if(!mobile) $(this).children('.p_actions').visibilityToggle();
  });


  $("#loadProjects").click(function(){
    $.ajax({
      type: "POST",
      url: "php/functions.php",
      data: {name:t},
      success: function(data){
        $("#projects").html(data);
      }
    });
  });


  $(".projectList a").last().addClass('last');


  $("#addProject").click(function(){
    $("#addProject").toggleClass('rotate');
    $('.addProjectForm').slideToggle("fast", "linear");
    $('#newProject').focus();
  });

  $("#holder").click(function(){
    $("#holder").toggleClass('rotate');
    $('#upload').addClass('mobile');
    $('#newMarkdown').slideToggle();
    $('#newMarkdown').children(".c3edit").children(".editareafield").val('');
    $('#newMarkdown').children(".c3").children(".content").html('');
    $('#newMarkdown').children(".c3edit").children(".editareafield").trigger('autosize.resize');  
  });


  $(".discardAdd").click(function() {
    $('#newMarkdown').slideToggle("normal", function() {
    $('#upload').removeClass('mobile');
    $("#holder").toggleClass('rotate');
  });

});



// add new project
$("#np").click(function(){
  var project = $('#newProject').val();
  $.ajax({
    type: "POST",
    url: "php/addProject.php",
    data: {newProject:project},
    success: function(data){
      location.reload();
    }
  });
});

$('#newProject').keypress(function (e) {
  if (e.which == 13) {
    var project = $('#newProject').val();
  $.ajax({
    type: "POST",
    url: "php/addProject.php",
    data: {newProject:project},
    success: function(data){
      location.reload();
    }
  });
  }
});



// DELETE ITEM - swipe
$(document).on("click",".p_delete",function(e){ 
  var project = $(this).parent().parent().parent().attr('href');
  project = project.substring(project.lastIndexOf('=')+1);
 
  e.preventDefault();

  if (confirm('Do you really want to delete the project?')) {
    $.ajax({
      type: "POST",
      url: "php/deleteProject.php",
      data: {project:project},
      success: function(data){          
        location.reload();
      }
    });
  } 
});

$('#markdownhelp').click(function(e) {
  $('#mdhelp').slideToggle();
});


$(".p_download").click(function(e){
  var project = $(this).parent().parent().parent().attr('href');
  project = project.substring( project.lastIndexOf('=')+1 );

  e.stopPropagation();
  e.preventDefault();
  window.location.href= 'php/zipProject.php?project='+project; 
  
});


// DOWNLOAD ITEM - button
$(document).on("click",".download-big",function(e){ 
  var itemId = $(this).parent().parent().parent().parent().children('.itemId').html();
  var project = $('.activeProject').html();
  var ext = itemId.split('.').pop();

 window.location.href= 'php/downloadNode.php?project='+project+'&itemId='+itemId+'&ext='+ext;
  $.ajax({
    type: "POST",
    url: "php/downloadImage.php",
    data: {project:project, itemId:itemId},
    success: function(data){  
       window.location.href=data; 
      //location.reload();
    }
  });
});



$(document).on("click",".p_update",function(e){ 
  alert("delete");
  e.preventDefault();
});



// UPDATE PROJECT ITEM

$(document).on("click",".addPost",function(e){ 
  var itemContent = $(this).parent().children('.editareafield').val();
  var project = $(this).parent().parent().parent().children('.activeProject').html();

  $.ajax({
    type: "POST",
    url: "php/addNode.php",
    data: {project:project, itemContent:itemContent},    
    success: function(data){
      location.reload();
    }
  });
});


// DISCARD UPDATE PROJECT ITEM
$(document).on("click",".discardUpdate",function(e){ 
  $(this).parent().children('.backup').html()  
  $(this).parent().parent().children(".c3").children(".ncontent").children(".content").html($(this).parent().children('.backup').html() );

  $(this).parent().parent().children(".c3").toggleClass("edit-mode");    
  $(this).parent().parent().children(".c3edit").toggle();
  
  if(!mobile)
    $(this).parent().parent().children(".c3").children(".ncontent").children('.actions').visibilityToggle();
 
  $(this).parent().parent().children(".snap-drawers").visibilityToggle();
  
});




// UPDATE PROJECT ITEM
$(document).on("click",".save",function(e){	
	var itemContent = $(this).parent().children('.editareafield').val();
	var itemId = $(this).parent().parent().children('.itemId').html();
  var project = $(this).parent().parent().parent().children('.activeProject').html();
  var align  = $(this).parent().parent().children('.alignment').html()
  
  $(this).parent().parent().children(".c3").children('.actions').visibilityToggle();
  
  $.ajax({
    type: "POST",
    url: "php/updateItem.php",
    data: {project:project, itemContent:itemContent, itemId:itemId},
    success: function(data){
      location.reload();
    }
  });

});


// DELETE ITEM - swipe
$(document).on("click",".delete",function(e){	
	var itemId = $(this).parent().parent().parent().children('.itemId').html();
  var project = $('.activeProject').html();
 if (confirm('Do you really want to delete this node?')) {
  $.ajax({
    type: "POST",
    url: "php/deleteNode.php",
    data: {project:project, itemId:itemId},
    success: function(data){					
      location.reload();
    }
  });
}
});

 

// DELETE ITEM - button
$(document).on("click",".delete-big",function(e){ 
  var itemId = $(this).parent().parent().parent().parent().children('.itemId').html();
  var project = $('.activeProject').html();
   if (confirm('Do you really want to delete this node?')) { 
  $.ajax({
    type: "POST",
    url: "php/deleteNode.php",
    data: {project:project, itemId:itemId},
    success: function(data){          
     location.reload();
    }
  });
}
});





var state = 1;


$(document).on("click",".edit",function(e){	
  $(this).parent().parent().parent().children(".snap-drawers").visibilityToggle();
  var currentContent = $(this).parent().parent().parent().children(".c3").children(".ncontent").children(".content").html();

  $(this).parent().parent().parent().children(".c3edit").children(".backup").html(currentContent);
  
  var t = toMarkdown(currentContent);
  $(this).parent().parent().parent().children(".c3edit").children(".editareafield").val(t);
  $(this).parent().parent().parent().children(".c3").toggleClass("edit-mode");
  
  $(this).parent().parent().parent().children(".c3edit").toggle();
  $(this).parent().parent().parent().children(".c3edit").children(".editarea").focus();
  $(this).parent().parent().parent().children(".c3edit").children(".editareafield").trigger('autosize.resize');
});


$(document).on("click",".edit-big",function(e){ 
  //$(this).children('.c3').find('.actions').css('visibility', 'hidden'); 

  $(this).parent().parent().parent().parent().children(".snap-drawers").visibilityToggle();
  $(this).parent().visibilityToggle();
 
  var currentContent = $(this).parent().parent().children(".content").html();

  $(this).parent().parent().parent().parent().children(".c3edit").children(".backup").html(currentContent);
  
  var t = toMarkdown(currentContent);
  $(this).parent().parent().parent().parent().children(".c3edit").children(".editareafield").val(t);
  $(this).parent().parent().parent().parent().children(".c3").toggleClass("edit-mode");
  
  $(this).parent().parent().parent().parent().children(".c3edit").toggle();
  $(this).parent().parent().parent().parent().children(".c3edit").children(".editarea").focus();
  $(this).parent().parent().parent().parent().children(".c3edit").children(".editareafield").trigger('autosize.resize');
});





$(document).on('keyup', '.editareafield', function(e) {
 var md = $(this).parent().children(".editareafield").val();

 var converter = new Showdown.converter();
 var postmd = converter.makeHtml(md);
 console.log(postmd);

 $(this).parent().parent().children(".c3").children(".ncontent").children(".content").html( postmd );
 $(this).parent().children(".editareafield").trigger('autosize.resize');

});

var typewatch = (function(){
  var timer = 0;
  return function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  }  
})();



$("#projects").change(function() {
  var project = $(this).val();
 
  $.ajax({
    type: "POST",
    url: "php/getProject.php",
    data: {project:project},
    success: function(data){
      $("#project").html(data);
      $("#project").class("content");
    }
  });
});




var holder = document,
  tests = {
      filereader: typeof FileReader != 'undefined',
      dnd: 'draggable' in document.createElement('span'),
      formdata: !!window.FormData,
      progress: "upload" in new XMLHttpRequest
    }, 
  support = {
    filereader: document.getElementById('filereader'),
    formdata: document.getElementById('formdata'),
    progress: document.getElementById('progress'),
  },
  acceptedTypes = {
    'image/png': true,
    'image/jpeg': true,
    'image/gif': true
  },
  progress = document.getElementById('uploadprogress'),
  fileupload = document.getElementById('upload');

  "filereader formdata progress".split(' ').forEach(function (api) {
    if (tests[api] === false) {
      support[api].className = 'fail';
    } else {
      // FFS. I could have done el.hidden = true, but IE doesn't support
      // hidden, so I tried to create a polyfill that would extend the
      // Element.prototype, but then IE10 doesn't even give me access
      // to the Element object. Brilliant.
     // support[api].className = 'hidden';
    }
});





    function previewfile(file) {
    if (tests.filereader === true && acceptedTypes[file.type] === true) {
      var reader = new FileReader();
      reader.onload = function (event) {
        var image = new Image();
        image.src = event.target.result;

          //image.width = 250; // a fake resize

         holder.getElementById('holder').appendChild(image);
      };

      reader.readAsDataURL(file);
      }  else {
        holder.innerHTML += '<p>Uploaded ' + file.name + ' ' + (file.size ? (file.size/1024|0) + 'K' : '');
        console.log(file);
      }
    }

    function readfiles(files) {
      //debugger;
      var formData = tests.formdata ? new FormData() : null;
      for (var i = 0; i < files.length; i++) {
        if (tests.formdata){
          formData.append('file', files[i]);
          //var p = document.getElementById('activeProject').value;
          var p = $('.activeProject').html(); 
          formData.append('project', p);
        } 

        previewfile(files[i]);
      }

      // now post a new XHR request
      if (tests.formdata) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'php/up.php');
        xhr.onload = function(data) {
          console.log(data);
          progress.value = progress.innerHTML = 100;

          //var project = document.getElementById('activeProject').value;
          var project = $('.activeProject').html(); 
          
          location.reload();
      };

      if (tests.progress) {
        xhr.upload.onprogress = function (event) {
          if (event.lengthComputable) {
            var complete = (event.loaded / event.total * 100 | 0);
            progress.value = progress.innerHTML = complete;
          }
        }
      }
      
      xhr.send(formData);
    }
  }

  if (tests.dnd) { 
    holder.ondragover = function () { this.className = 'hover'; return false; };
    holder.ondragend = function () { this.className = ''; return false; };
    holder.ondrop = function (e) {


      this.className = '';
      e.preventDefault();
      readfiles(e.dataTransfer.files);
    }
    
    $("#uup").change(function(e){
      e.preventDefault();
      console.log(e);
      readfiles(e.target.files);
      $("#holder").toggleClass('rotate');
      $('#upload').removeClass('mobile');
      $('#newMarkdown').fadeToggle("fast", "linear");
    });

  } else {
    fileupload.className = 'hidden';
    fileupload.querySelector('input').onchange = function () {
      readfiles(this.files);
    };
  }

});