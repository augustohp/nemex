<?php
require 'bootstrap.php';

$tests = array();
$tests[] = function() {
    $minimumVersion = '5.3.0';
    return array(
        version_compare(PHP_VERSION, $minimumVersion, '>='),
        sprintf('At least version %s of PHP should be used. You can try and use it, but things will hardly work.', $minimumVersion)
    );
};
$tests[] = function() {
    return array(
        extension_loaded('iconv'),
        '`iconv` (PHP) extension enabled. Without it, you can gave problems with special (graphical accents) caracters.'
    );
};
$tests[] = function() {
    return array(
        extension_loaded('gd'),
        '`gd` (PHP) extension enabled. You can use Nemex without it, but you will not be able to upload images.'
    );
};
$tests[] = function() {
    return array(
        is_writable(NEMEX_PATH.'projects'),
        '"projects" folder is writable. Without that, you won\'t be able to use Nemex.'
    );
};
$tests[] = function() {
    $classExists = class_exists('Cfg');
    return array(
        $classExists,
        'You need to configure the authentication of the application, please, read the related setion on readme. (Missing `config.php`)'
    );
};

function run_tests(array $suite) {
    $resultsString = '<ol class="tests">%s</ol>';
    $testString = '<li class="%s">%s</li>';
    $results = array();
    foreach ($suite as $test) {
        $result = $test();
        $success = array_shift($result);
        $description = array_shift($result);
        $cssClassToUse = ($success) ? 'ok' : 'fail' ;
        $results[] = sprintf($testString, $cssClassToUse, $description);
    }

    return sprintf($resultsString, implode('', $results));
}
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="viewport" content="width=device-width, minimal-ui">
		<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
		<title>nemex.ioi: Setup check</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<link rel="stylesheet" type="text/css" href="css/style-res.css">
		<link rel="apple-touch-icon" sizes="60x60" href="touch-icon-iphone.png">
		<link rel="apple-touch-icon" sizes="76x76" href="img/touch-icon-ipad.png">
		<link rel="apple-touch-icon" sizes="120x120" href="img/touch-icon-iphone-retina.png">
		<link rel="apple-touch-icon" sizes="152x152" href="img/touch-icon-ipad-retina.png">
		<script> var noElements = 0; </script>
		<link rel="icon" type="image/png" href="favicon.png" />
<style>
body {
    margin: 30px;
}
.tests {
    list-style: decimal outside;
    font-size: 2em;
    margin: 100px 25%;
}
.tests li {
    margin: 30px 0;
}
.ok {
    color: green;
    background: none;
}
.fail {
    color: red;
    background: none;
}
</style>
	</head>
	<body>
        <h1>Nemex: Configuration check</h1>

        <fieldset>
            <legend>Instructions</legend>

            <p>
                The tests run as the page loads, each item on the list is a test.
                The result can be <span class="ok">good</span> or <span class="fail">bad</span>.
            </p>
            <p>
                If you have <span class="fail">problems</span> you can relate to the README file.
            </p>
        </fieldset>

        <?php echo run_tests($tests) ?>
	</body>
</html>
