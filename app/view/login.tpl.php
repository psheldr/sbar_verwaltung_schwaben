<div id="login_box">
<form method="post" action="index.php?action=do_login">
    <label>User</label><input type="text" name="username" /><br />
    <label>Passwort</label><input type="password" name="passwort" /><br />
    <input class="submit_btn" type="submit" value="anmelden" /><br />
</form>
<p class="clear error_text">
    <?php echo $_SESSION['fehler_hinweis']; unset($_SESSION['fehler_hinweis']);  ?>
</p>
</div>