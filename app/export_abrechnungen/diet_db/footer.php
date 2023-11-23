<script>
//Javascript zum weiterleiten nach edit.php,
//incl. Übergabe der id für das SQL-Statement
//Funktion: gebe die ID,
//der angeklickten Zeile an edit.php weiter
function editRow(){
  var table = document.getElementById("table");
  var rows = table.getElementsByTagName("tr");
  //Schleife über die Tabellenzeilen
  for (i = 1; i < rows.length; i++) {
    row = table.rows[i];
    row.onclick = function(){
      var cell = this.getElementsByTagName("td")[0]; //erste Spalte wählen (ID)
      var id = cell.innerHTML; //Inhalt merken
      window.location.replace("edit.php?id="+id); //GET Methode simulieren, zur Übergabe der ID
    };
  }
}
//Sicherheitsabfrage für's löschen
function delForm(){
  if(window.confirm("Die Spalte wird unwiderruflich gelöscht.")){
    var id = document.getElementById("delColumn");
    id.submit(); //Ok-Dialog -> Form 'delColumn' ausführen
  }
  else{

  }
}
//Sicherheitsabfrage
function delRowForm(){
  if(window.confirm("Die Zeile wird unwiderruflich gelöscht.")){
    var id = document.getElementById("delRowForm");
    id.submit();
  }
  else{

  }
}
function toggle_visibility() {
  var e = document.getElementById("hidden");
  if(e.style.display == 'block')
  e.style.display = 'none';
  else
  e.style.display = 'block';
}
//Script zum deaktivieren der versteckten Formulare, falls checkbox aktiv,
//da sonst beide Werte versendet werden, was den zurückgegebenen Array
//größer macht als es Spalten in der Tabelle gibt
function disableHidden(id){
  if(document.getElementById('check'+id).checked) {
    document.getElementById('hidden'+id).disabled = true;
  }
  else{
    document.getElementById('hidden'+id).disabled = false;
  }
}
//Diese Funktion prüft live, dass keine 'true' oder 'false'
//Werte eingegeben werden. Diese sind Anwendungsintern und dürfen nicht verwendet werden
function validate(){
  //Alle Input-Elemente per Klasse sammeln
  var inputs = document.getElementsByClassName("inputs");

  //For Schleife über die gesamten Input-Elemente für jedes 'onchange'-Event
  for(var i=0; i < inputs.length; i += 1){
    //falls Feld leer oder voller Leerzeichen -> Inputfeld Rot markieren
    if(inputs[i].value == '' || inputs[i].value.replace(/^\s+|\s+$/g, '').length == 0){
      inputs[i].style.borderColor = 'red'; //Input Rahmen Rot hervorheben
      inputs[i].style.color = 'red';    //Schrift Rot hervorheben
    }
    else{
      //Rücknahme der Input hervorhebungen
      inputs[i].style.borderColor = 'grey';
      inputs[i].style.color = 'black';
      document.getElementById('submit').style.backgroundColor = '#ff870d';
      document.getElementById('submit').disabled=false;
    }

    if(inputs[i].value == 'true' || inputs[i].value == 'false'){ //'true' oder 'false' ?
    inputs[i].style.borderColor = 'red'; //Input Rahmen Rot hervorheben
    inputs[i].style.color = 'red';    //Schrift Rot hervorheben
    document.getElementById('submit').style.backgroundColor = 'grey'; //Submit-Button ausgrauen
    document.getElementById('submit').disabled=true; //Submit-Button deaktivieren
    break; //Aus der Schleife springen, da falls der nächste Input kein 'true' oder 'false' hat
    //die Änderungen im else-Zweig Rückgängig gemacht werden.
     }
}
}
function verifypasswd() {

    // Define the Dialog and its properties.
    $("#dialog").dialog({
        dialogClass: "success",
        hide: { effect: "blind", duration: 1000 },
        closeText: "X",
        resizable: false,
        modal: true,
        title: "Diätfeld bearbeiten",
        height: 250,
        width: 400,
        buttons: {
                "Übernehmen": function () {
                $(this).dialog('close');
                callback();
            },
                "Abbrechen": function () {
                $(this).dialog('close');
            }
        }
    });
}

function callback() {
    passwd = $("#diaetpassword").val();
    passwd = $.md5(passwd);
    text = $("#diaettext").val();
    hash = $('#diaethiddenhash').val();

    if(passwd === hash){
      $("#diaetinput").val(text);
      $("#diaetinputdisabled").val(text);
    }
    else{
      alert("Das Passwort ist nicht korrekt");
    }
}
</script>
</html>
