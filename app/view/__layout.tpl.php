<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title></title>
        <meta name="description" content="" />
        <!-- Kalender -->
        <style type="text/css">@import url(calendar/calendar-system.css);</style>
        <script type="text/javascript" src="calendar/calendar.js"></script>
        <script type="text/javascript" src="calendar/lang/calendar-de_de.js"></script>
        <script type="text/javascript" src="calendar/calendar-setup.js"></script>
        <!-- Kalender Ende -->
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="language" content="de" />
        <meta name="robots" content="index,follow" />
        <meta name="audience" content="alle" />
        <meta name="page-topic" content="Dienstleistungen" />

        <?php if ($action == 'infoticker_anzeige') { ?>
            <meta http-equiv="refresh" content="10" />
        <?php } ?>

        <link rel="stylesheet" type="text/css" href="css/styles.css" media="screen, print" />
        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.js"></script>
        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script>
        <script type="text/javascript" src="js/jquery.alert.js"></script>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
            <link rel="stylesheet" type="text/css" href="js/jquery.alerts.css" media="screen, print" />
            <style>
                /* Custom dialog styles */
                #dialog {
                }
            </style>
            <script type="text/javascript">
                $(document).ready(function () {

                    function slideout() {
                        setTimeout(function () {
                            $("#response").slideUp("slow", function () {
                            });

                        }, 2000);
                    }

                    $("#response").hide();
                    $(function () {
                        $("#list ul").sortable({opacity: 0.8, cursor: 'move', update: function () {

                                var order = $(this).sortable("serialize") + '&update=update';
                                $.post("updateList.php", order, function (theResponse) {
                                    $("#response").html(theResponse);
                                    $("#response").slideDown('slow');
                                    slideout();
                                });
                            }
                        });
                    });
                    $(function () {
                        $("#list_prod ul").sortable({opacity: 0.8, cursor: 'move', update: function () {

                                var order = $(this).sortable("serialize") + '&update=update';
                                $.post("updateList_prod.php", order, function (theResponse) {
                                    $("#response").html(theResponse);
                                    $("#response").slideDown('slow');
                                    slideout();
                                });
                            }
                        });
                    });

                    $('.deaktivier_link').click(
                            function (event) {
                                event.preventDefault();
                                $("#dialog_box").dialog('open');
                                kid = $(this).attr('kid');
                                $('#hidden_kid').val(kid);
                            });

                    $(".prompts").dialog({dialogClass: "alert",
                        autoOpen: false,
                        modal: true,
                        buttons: {
                            'abbrechen': function () {
                                $('#dialog_box').dialog('close');
                            },
                            "weiter": function () {
                                $('#check_deaktivierung').dialog('open');
                            }
                        }
                    });

                    $(".confirms").dialog({dialogClass: "alert",
                        autoOpen: false,
                        buttons: {
                            'abbrechen': function () {
                                $('#check_deaktivierung').dialog('close');
                            },
                            "weiter": function () {
                                $('#check_deaktivierung').dialog('close');
                                $('#dialog_box').dialog('close');
                                $('#endtag_form').submit();
                            }
                        }
                    });

                });

                $(function () {
                    $("#dialog_box").dialog();
                    $("#check_deaktivierung").dialog();
                });
            </script>


            <?php if ($action == 'speiseplaene') { ?>
                <script type="text/javascript">
                    function checkAnzahlPortionen(object) {
                        if (object.value.length >= 3) {
                            object.parentNode.style.backgroundColor = 'red';
                        }
                    }
                </script>
            <?php } ?>
            <?php if ($action == 'infoticker_anzeige') { ?>
                <style type="text/css">
                    #content_box {
                        width: auto;
                        margin-right: 20px;
                    }

                    #infoticker_form select {
                        font-weight: bold;
                        padding: 0px 5px;
                    }

                    .infoticker_link {
                        display: block;
                        border: 3px solid red;
                        text-decoration: none;
                        font-weight: bold;
                        color: red;
                        margin-bottom: 20px;
                    }
                    .infoticker_link:hover {
                        border: 3px solid green;
                    }
                    .infoticker_link:hover .datum {
                        background: green;
                        color: #fff;
                    }
                    .infoticker_link:hover .text {
                        color: green;

                    }
                    .infoticker_link .datum, .infoticker_link .text {
                        display: block;
                        font-size: 22px;
                        padding: 10px;
                    }
                    .infoticker_link .datum {
                        background: red;
                        color: #fff;
                    }

                    .infoticker_link .text {
                        font-size: 30px;

                    }
                    .no_info_text {
                        font-size: 24px;
                        font-weight: bold;
                    }
                    .datum_zusatz {
                        font-size: 18px;
                    }

                    .infoticker_zukunft {
                        border: 3px solid #888;
                    }
                    .infoticker_zukunft .datum {
                        background: #888;
                        color: #fff;
                    }
                    .infoticker_zukunft .text {
                        color: #888;
                        background: #fff;
                    }
                </style>
            <?php } ?>

    </head>
    <body>

        <?php if ($_SESSION['logged_in_user'] && $action != 'infoticker_anzeige') { ?>
            <div id="navi_box">
                <?php require 'view/navi.tpl.php' ?>
                <div class="clear"></div>
            </div>
        <?php } ?>

        <div id="content_box">
            <?php require 'view/' . $action . '.tpl.php' ?>
        </div>
    </body>
</html>