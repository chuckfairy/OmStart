<?php
defined('DB_SERVER') ? null : define('DB_SERVER', 'localhost');
defined('DB_USER')   ? null : define('DB_USER',   'root');
defined('DB_PASS')   ? null : define('DB_PASS',   'poop1234');
defined('DB_NAME')   ? null : define('DB_NAME',   'omstart');

defined("SRC_DIR")   ? null : define("SRC_DIR", "omstart-v1");

defined("DEFAULT_BLOG") ? null : define("DEFAULT_BLOG",
htmlspecialchars("<style>#someTag {some: css;}</style>
<h2>The Header</h2>
<div id='contentDiv'>
  <p>The Main action</p>
</div>

<script>
//Sample Comment Here
</script>")
);

?>
