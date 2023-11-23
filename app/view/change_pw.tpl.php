
<form method="post" action="index.php?action=do_change_pw">
        <label class="form_label">Aktuelles Passwort</label>
        <input class="form_input" type="text" name="old_pw" /><br />

        <label class="form_label" style="color: red;" id="neues_pw_label" >Neues Passwort*</label>
        <input onkeyup="checkPwL()" id="neues_pw_input" class="form_input" type="password" name="new_pw" /><br />

        <label class="form_label">Neues Passwort bestätigen*</label>
        <input class="form_input" type="password" name="new_pw_check" /><br />

        <p style="font-size: 11px;">*Das Passwort muss mindestens 8 Zeichen lang sein.</p>
        <input class="submit_btn" type="submit" value="Änderung speichern" onmouseover="javascript:this.style.cursor = 'pointer'" />
    </form>
    <p style="color:red;"><?php echo $_SESSION['fehler']; unset($_SESSION['fehler']); ?></p>
    