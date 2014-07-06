<?php require_once("../omstart/initialize.php");?>

<?php
if(isset($_POST["submit"])) {
	
	$blog_object = new TableObject("blog", ["title", "author"]);
	$blog_object->title = $_POST["title"];
	$blog_object->author = $_POST["author"];
	$blog_object->tags = $_POST["tags"];
	$blog_object->url = join(".", [clean_file($blog_object->title), "html"]);
	$session->set_message($blog_object->url);
	///blog_file(DEFAULT_BLOG, "blog".DS.$blog_object->content);
	$blog_object->code = DEFAULT_BLOG;
	if($blog_object->save()) {
		$session->set_message("New Blog Post Created");
	} else {
		$session->set_message("Failed to create");
	}
	unset($_POST["submit"]);
}

$blog_object = new TableObject("blog");
$blog_data = $blog_object->find_all();
?>
<html>
<head>
<title>{Blog Editor}</title>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<link href="_css/omstart.css" rel="stylesheet">
<script src="_js/jquery-1.9.1.min.js"></script>
</head>
<body>

<!---------EDITOR DATA UI--------->
<div id="editIcons">
	<a href="javascript:void(0)" id="saveIcon"><img src="_assets/icons/BeOS_Floppy.png">Save</a>
	<a href="javascript:void(0)" id="newIcon"><img src="_assets/icons/BeOS_clipboard.png">New</a>
	<a href="#delete" id="deleteIcon"><img src="_assets/icons/BeOS_Trash_full.png">Delete</a>
	<a href="./"><img src="_assets/icons/BeOS_Home.png">Home</a>
</div>

<div id="successBox">
	<img src="_assets/icons/BeOS_NetPositive.png" width="40" height="40" />
	<span>Save Successful</span>
	<p>MESSAGE</p>
</div>

<div id="failBox">
	<img src="_assets/icons/BeOS_stop.png" width="40" height="40" />
	<span>Failure</span>
	<p>MESSAGE</p>
</div>

<div id="loadingPage">
	<img src="_assets/omstart_logo.png">
	<h2>OmStart {Blog Editor}</h2>
</div>

<!---------POST EDITOR NAV--------->
<h1>OmStart {Blog Editor}</h1>

<div id="blogFileManager">
	<h4>Choose Blog Post</h4>
	<img src="_assets/icons/BeOS_Globe.png" alt="BeOS_Globe.png"/>
	<div id="fileList">
		<?php while($blog = $database->assoc($blog_data)) {
			echo "<a href='#blogId{$blog['id']}'>{$blog['title']}</a>";		
		}?>
	</div>
	<?php $session->output_messages("li");?>
</div>

<div id="workingPost"><h4>Working Post:</h4><a href="#">New</a></div>
<hr/>

<!---------DATA FORM AND CODE EDITOR --------->
<form action="../controllers/writeblog.php" method="post">

<input type="hidden" name="id" id="postId"> 

<table class="editorTable">
	<tr>
		<td>Title</td>
		<td><input type="text" name="title" id="postTitle" placeholder="Title"></td>
	</tr>
	
	<tr>
		<td>Author</td>
		<td><input type="text" name="author" id="postAuthor" placeholder="Author(You)"></td>
	</tr>
</table>

<div id="editorNav">
	<a href="javascript:void(0)" id="html">HTML</a>
	<a href="javascript:void(0)" id="css">CSS</a>
	<a href="javascript:void(0)" id="js">JS</a>
</div>


<pre id="editor" contentEditable="true"></pre>
<textarea name="tags" id="postTags" style="margin-top:20px;cols:20;">tags, separated by, commas</textarea>
<button type="submit" name="submit">Create Post!</button>
</form>

<!---------New Panel--------->
<div id="newPanel">
	<a href="javascript:void(0)" id="newExit">X</a>
	<h2>Create a newPost</h2>
	
	<form action="editor.php" method="post">
		<input type="hidden" name="table" value="blog">
		<h3>Blog Post Title</h3>
		<input type="text" name="title" placeholder="Title">
		<h3>Author</h3>
		<input type="text" name="author" placeholder="Author(You)">
		<textarea name="tags">Tags, separated, by commas</textarea>
		<button type="submit" name="submit">Create New Post!</button>
	</form>
	
	<p>Don't worry. You can always edit later.</p>
</div>

<!---------HTML RENDER--------->
<h2 id="htmlPreview">Blog Post Preview</h2>
<hr/>
<div id="htmlrender"></div>

<script src="_js/ace-src/ace.js" type="text/javascript"></script>
<script src="_js/blogeditor.js" type="text/javascript"></script>

</body>
</html>