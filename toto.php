<form method="POST" action="">
<input type="text" name="login" placeholder="Login">
<input type="password" name="password" placeholder="Password">
<input type="submit" value="Submit">
</form>
 
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
var_dump($_POST);

}
?>
 