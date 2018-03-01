<!DOCTYPE html>
<html lang="en">
<head prefix="dcterms: http://purl.org/dc/terms/">
    <title>EADitor Institutional Repository View</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"-->
    <link rel="stylesheet" href="styles/bootstrap.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="./styles/main.css" />
    <link rel="stylesheet" type="text/css" href="./styles/chronlogy.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <style>
        /* Remove the navbar's default margin-bottom and rounded borders */
        .navbar {
            margin-bottom: 0;
            border-radius: 0;
        }

        /* Set height of the grid so .sidenav can be 100% (adjust as needed) */
        .row.content {height: 450px}

        /* Set gray background color and 100% height */
        .sidenav {
            padding-top: 20px;
            /*background-color: #f1f1f1;*/
            height: 100%;
        }

        /* Set black background color, white text and some padding */
/*        .footer {
            background-color: #555;
            color: white;

        }*/
        .footer {
            position: relative;
            right: 0;
            bottom: 0;
            left: 0;
            padding: 1rem;
            background-color: #555;
            color:white;
            text-align: center;
        }
        /* On small screens, set height to 'auto' for sidenav and grid */
        @media screen and (max-width: 767px) {
            .sidenav {
                height: auto;
                padding: 15px;
            }
            .row.content {height:auto;}
        }


    </style>
    <script>
        $(document).ready(function() {
            $("input#username").change(function () {
                $('#brsel').empty();
                $('#reposel').empty();
                $("#dirsel").empty();
                $("#fileTable tbody").empty();
                   document.getElementById("fileTableDiv").style.visibility = "hidden";
                   document.getElementById("fileTable").style.visibility = "hidden";

                var gitUser = document.getElementById("username").value;

               // var gitRepositoryName = document.getElementById("repo").value;
                if(gitUser != null) {
                    $.ajax({
                        url: "https://api.github.com/users/" + gitUser + "/repos",
                        jsonp: true,
                        method: "GET",
                        dataType: "json",
                        success: function (res) {
                            $('#reposel').append($('<option>', {
                                value: "select repo",
                                text: "Select your Repository"
                            }));

                            $.each(res, function () {
                                $('#reposel').append($('<option>', {
                                    value: this['name'],
                                    text: this['name']
                                }));


                            });
                        }
                    });
                }
            });
            $("#reposel").change(function() {
                $('#brsel').empty();
                $('#dirsel').empty();
                $("#fileTable tbody").empty();
                document.getElementById("fileTableDiv").style.visibility = "hidden";
                document.getElementById("fileTable").style.visibility = "hidden";
                var gitUser = document.getElementById("username").value;
                var repoSel = this.value;
                if(repoSel != "select repo") {
                    $.ajax({
                        // https://api.github.com/repos/:username/:repositoryname/branches
                        url: "https://api.github.com/repos/" + gitUser + "/" + repoSel + "/branches",
                        jsonp: true,
                        method: "GET",
                        dataType: "json",
                        success: function (res) {
                            $('#brsel').append($('<option>', {
                                value: "select branch",
                                text: "Select your Branch"
                            }));
                            $.each(res, function () {
                                $('#brsel').append($('<option>', {
                                    value: this['name'],
                                    text: this['name']
                                }));

                            });
                        }
                    });
                }

            });

            $("#brsel").change(function() {
                $('#dirsel').empty();
                $("#fileTable tbody").empty();
                document.getElementById("fileTableDiv").style.visibility = "hidden";
                document.getElementById("fileTable").style.visibility = "hidden";
                var gitUser = document.getElementById("username").value;
                var repoSel = $("#reposel").val();
                var brSel = this.value;
                if(brSel != "select branch") {
                    $.ajax({
                        //https://api.github.com/repos/dkarnati174/EADs/git/trees/master
                        url: "https://api.github.com/repos/" + gitUser + "/" + repoSel + "/git/trees/" + brSel,
                        jsonp: true,
                        method: "GET",
                        dataType: "json",
                        success: function (res) {
                            $('#dirsel').append($('<option>', {
                                value: "select directory",
                                text: "Select a Directory"
                            }));
                            $.each(res, function (key, value) {
                                if (key == "tree") {
                                    $.each(value, function () {
                                        $('#dirsel').append($('<option>', {
                                            value: this['path'],
                                            text: this['path']
                                        }));
                                    });
                                }
                            });
                        }
                    });
                }
            });
            $("#dirsel").change(function() {
                var gitUser = document.getElementById("username").value;
                var repoSel = $("#reposel").val();
                var brSel = $("#brsel").val();
                var dirSel = this.value;
                $("#fileTable tbody").empty();
                document.getElementById("fileTableDiv").style.visibility = "hidden";
                document.getElementById("fileTable").style.visibility = "hidden";
                if(dirSel != "select directory") {
                    $.ajax({
                        //https://api.github.com/repos/dkarnati174/EADs/git/trees/master
                        url: "https://api.github.com/repos/" + gitUser + "/" + repoSel + "/git/trees/" + brSel + ":" + dirSel,
                        jsonp: true,
                        method: "GET",
                        dataType: "json",
                        success: function (res) {
                            $('#dirsel').append($('<option>', {
                                value: "select directory",
                                text: "Select a Directory"
                            }));
                            var i = 1;
                            document.getElementById("fileTableDiv").style.visibility = "visible";
                            document.getElementById("fileTable").style.visibility = "visible";
                            $.each(res, function (key, value) {
                                if (key == "tree") {
                                    $.each(value, function () {
                                          var link = "https://raw.githubusercontent.com/"+gitUser+"/"+repoSel+"/"+brSel+"/"+dirSel+"/"+this['path']
                                        $('#fileTable').append('<tr><td>' + i + '</td>' +

                                            '<td><a href="'+link+'">' + this['path'] + '</a></td>'+
                                            '<td><input type="checkbox" align="center" checked class="form-check-input" name="eadFileSelect" value="' + this['path'] + '"></td></tr>');
                                        i++;

                                    });

                                }
                            });
                        }
                    });
                }
            });
            $('button#validate').click(function () {
                var gitUser = document.getElementById("username").value;
                var repoSel = $("#reposel").val();
                var brSel = $("#brsel").val();
                var dirSel = $("#dirsel").val();
                var fileList = [];
                $.each($("input:checked[name='eadFileSelect']"), function () {
                    var filename = this.value;
                    if(filename.indexOf(".xml") != -1) {
                        fileList.push(this.value);
                    }
                });
                if(fileList.length == 0){

                    alert("Please select atleast one file");
                }
                $.post("<?php echo base_url("?c=eadharvester&m=validate");?>", {
                    username: gitUser,
                    repository: repoSel,
                    branch: brSel,
                    directory: dirSel,
                    fileList: JSON.stringify(fileList)

                }).done(function (response) {

                    alert(response);
                });


            });
            $("input#selectall").click(function(event) {
                if(this.checked) {
                    $(".form-check-input").prop('checked', true);
                }else{
                    $(".form-check-input").prop('checked', false);

                }
            });
        });


    </script>
    <!--script>
          $(document).ready(function() {
            $(".fileRow:even").css("background-color","#f2f2f2");
            $(".fileRow:odd").css("background-color","#ffffff");
        });
    </script-->
    <?php
?>
</head>
<body>


<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <!--a class="navbar-brand" href="/"><img src='https://www.empireadc.org/sites/www.empireadc.org/files/ead_logo.gif' /></a-->
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav">
                <!--li class="active"><a href="#">Home</a></li-->
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <!--li><a href="#"><span class="glyphicon glyphicon-log-in"></span> Login</a></li-->
                <!--li><a href="#">About</a></li>
                <li><a href="#">Contact</a></li-->
                <li><a href='https://drive.google.com/open?id=1hsFy_xJ9uIP_wkRZjityXVdWVHSQF3X9eVALv2sMEo4' target='_blank'>Feedback/Issue</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="container-fluid text-center">
    <div class="row content">
        <!--Header Logo -->
        <div class="col-sm-2 sidenav">
            <a href='<?php echo base_url(); ?>'></a>
            <!--p><a href="#">Link</a></p>
            <p><a href="#">Link</a></p>
            <p><a href="#">Link</a></p-->
        </div>
        <div class="col-sm-4 text-right">
        </div>
        <!-- Form Content -->

        <div class="col-sm-8 text-left">


            <div class="container">
                <h3>Institution's Git Repo</h3>
                <hr>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Institution</label>
                            <div class="input-group"> <span class="input-group-addon"><span class="glyphicon glyphicon-home"></span></span>
                                <input type="text" class="form-control" name="institution" id="inst" placeholder="Institution's Name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Git User Name</label>
                            <div class="input-group"> <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                                <input type="text" class="form-control" name="username" id="username" placeholder="Git Repo User Name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Select Repository</label>
                            <div class="input-group"> <span class="input-group-addon"><span class="glyphicon glyphicon-th-list"></span></span>
                                <select class="form-control" name="repositorySelect" id="reposel">
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Select Branch</label>
                            <div class="input-group"> <span class="input-group-addon"><span class="glyphicon glyphicon-th-list"></span></span>
                                <select class="form-control" name="branchSelect" id="brsel">

                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Select Directory</label>
                            <div class="input-group"> <span class="input-group-addon"><span class="glyphicon glyphicon-indent-left"></span></span>
                                <select class="form-control" name="directorySelect" id="dirsel">
                                </select>
                            </div>
                        </div>
                        <div class="form-group" id="fileTableDiv" style="visibility: hidden">
                            <label> List of Files in the selected directory</label>

                        <table class="table table-striped" name="fileTable" id="fileTable" style="visibility: hidden">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>File Name</th>
                                <th>Selected files <br/> to validate &nbsp;(&nbsp;<input type="checkbox" checked name="eadFileSelect" id="selectall">&nbsp;All</input>&nbsp;)</th>

                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        </div>

                        <button class="btn btn-primary center-block" type="button" id="validate">Validate</button>
                    </div>
        </div>
</div>
            </div>
</div>
    </div>
    </br>
    <footer class="footer text-center">
        <p>Footer Text</p>

    </footer>
</body>

</html>








