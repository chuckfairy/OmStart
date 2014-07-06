<?php require_once("../initialize.php");?>

<?php

echo __DIR__;

?>
<html>
<head>
<title>Omstart</title>
<link href="_assets/design.css" rel="stylesheet" type="text/css">
</head>
<body>

<h1 id="logo">Omstart</h1>

<hr/>

<h2>Testing Area</h2>

<?php

$blog_data = new TableObject("blog");
$blog_data->filter_out("title", "Sample Post89");
$blogs = $blog_data::find_all(true);
print_r($blogs);

echo "<hr/><h2>Find All</h2>";
$blog_data->clear_filters();
print_r($blog_data->find_all());

echo "<hr/><h2>Find by</h2>";
print_r($blog_data->find_by("title", "Sample Post89"));
echo "<hr/><h2>Find by id</h2>";
print_r($blog_data->find_by_id("4"));
echo "<hr/><h2>Save()</h2>";
$blog_data->title = "WOWOWOW";
$blog_data->author = "CHUCK DUDE";
//$blog_data->save();



//print_r(get_class_methods($PDO));

?>

<hr/>

<h2>Session Messages</h2>
<?php $session->output_messages("li");?>

<h2>Last Sql</h2>


</body>
</html>
