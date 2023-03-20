<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FFXIV Raid Log</title>
    <link rel="stylesheet" type="text/css" href="css/index.css"/>


    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">

    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

    <link rel="icon" type="image/x-icon" href="/images/favicon.ico">
</head>
<body>
<?php
require_once 'header.php';
?>
<form id="add_form" class="form-inline well">
    <h1>Spieler/Charaktere anlegen</h1>
<div id="form_container_alt" class="container">
    <div class="row">
        <div class="col-md-12">
            <div data-role="dynamic-fields">
                <div class="form-inline form_distance">
                    <div class="form-group">
                        <label class="sr-only" for="field-name">Charakter Name</label>
                        <input type="text" class="form-control" id="field-name" placeholder="Charakter Name">
                    </div>
                    <div class="form-group">
                        <label for="sel1">Klasse:</label>
                        <select class="form-control" id="sel1">
                            <option value="0">Tank</option>
                            <option value="1">Heiler</option>
                            <option value="2">Melee DPS</option>
                            <option value="3">Ranged Physical DPS</option>
                            <option value="4">Ranged Magical DPS</option>
                        </select>
                    </div>
                    <button class="btn btn-danger" data-role="remove">
                        <span class="glyphicon glyphicon-remove"></span>
                    </button>
                    <button class="btn btn-primary" data-role="add">
                        <span class="glyphicon glyphicon-plus"></span>
                    </button>
                </div>  <!-- /div.form-inline -->
            </div>  <!-- /div[data-role="dynamic-fields"] -->
        </div>  <!-- /div.col-md-12 -->
    </div>  <!-- /div.row -->
</div>
    <button id="submit" type="submit" class="btn btn-default" data-toggle="modal" data-target="#add_modal">Submit</button>
</form>

<div class="modal fade" id="add_modal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Login Status</h4>
            </div>
            <div class="modal-body">
                <p id="add_answer"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<script>
    $(function() {
        // Remove button click
        $(document).on(
            'click',
            '[data-role="dynamic-fields"] > .form-inline [data-role="remove"]',
            function(e) {
                e.preventDefault();
                if(document.getElementsByClassName('btn-danger').length > 1 ){
                    $(this).closest('.form-inline').remove();
                }else{

                }

            }
        );
        // Add button click
        $(document).on(
            'click',
            '[data-role="dynamic-fields"] > .form-inline [data-role="add"]',
            function(e) {
                e.preventDefault();
                var container = $(this).closest('[data-role="dynamic-fields"]');
                new_field_group = container.children().filter('.form-inline:first-child').clone();
                new_field_group.find('input').each(function(){
                    $(this).val('');
                });
                container.append(new_field_group);
            }
        );
    });

    $('#submit').click(function (){
        event.preventDefault();
        let elements = document.getElementsByClassName('form_distance');
        let form_data = {};
        for (let i = 0; i < elements.length; i++) {
            form_data[i] = {'Name': elements[i].children[0].children[1].value,'Rolle': elements[i].children[1].children[1].value};
        }
        console.table(form_data);
        $.ajax({
            type: 'POST',
            url: '_add.php',
            data: form_data,
            success: function (response) {
                //location.reload();
                if(response == 1){
                    $('#add_answer').text('Charaktere erfolgreich angelegt! Die Seite wird aktualisiert');
                    setTimeout(function(){
                        //do what you need here
                        location.reload();
                    }, 3000);
                }else{
                    $('#add_answer').text('Anlegen von Charakteren fehlgeschlagen!');
                }


            }
        });

    });
</script>