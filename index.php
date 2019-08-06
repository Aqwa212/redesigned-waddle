<?php

session_start();
//include_once 'api.php';
    // if(!$_SESSION['user']){
    //     header('location:/crm/index.php'); //переадресация на страницу входа
    //     exit();
    // }

    // include_once 'db.php';
    // include_once 'api.php';
?>



<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script> -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script> -->

        <script src="jquery.csv.js"></script>

        <title>Звонки</title>
    </head>
    <body>
        <header>
            <nav class="navbar navbar-expand-sm bg-dark">
              <!-- Brand/logo -->
                <a class="navbar-brand" href="#">
                    <img src="logo.png" alt="logo" style="height:30px;">
                </a>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="page_1.php">Кнопка 1</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Кнопка 2</a>
                    </li>
                </ul>
            </nav>
            <br>
        </header>

        <div class="container">
            <div class="row">
                    <div class="col-lg-4">


<div class="input-group mb-3">
  <input id="ipt_date" type="date" class="form-control" aria-describedby="button-addon2">
  <div class="input-group-append">
    <button id="history" class="btn btn-outline-secondary" type="button" id="button-addon2">Получить данные</button>
  </div>
</div>


<!-- <input id="ipt_date" type="date" class="form-control"></input>
            <br>
            <br>
<button id="history" type="button" class="btn btn-primary">Получить данные</button> -->


                    </div>
                    <div class="col-sm-8">
<table id="tbl_calls" class="table">
  <thead class="thead-dark">
    <tr>
      <th scope="col">Сотрудник</th>
      <th scope="col">Исходящие</th>
      <th scope="col">Входящие</th>
      <th scope="col">Пропущенный</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
  </tbody>
</table>
                    </div>

            </div>
        </div>
    </div>

        <script>

            $(document).ready (function () {

                // var userId = 
                // $("#input0").text('Gjk');
                var now = new Date();

                var day = ("0" + now.getDate()).slice(-2);
                var month = ("0" + (now.getMonth() + 1)).slice(-2);

                var today = now.getFullYear()+"-"+(month)+"-"+(day) ;

                $('#ipt_date').val(today);

                $('#history').click();
            });



            $('#history').on("click", function(){
                //e.preventDefault();

                var start = $('#ipt_date').val();
                //console.log(start);
                // var end = new Date(start.getFullYear()+"-"+(start.getMonth() + 1)+"-"+(start.getDate() + 1));
                // console.log(end);
                $.ajax({
                //headers: { "Accept": "application/json"},
                    url: 'https://ld.megapbx.ru/sys/crm_api.wcgp?cmd=history&start='+ start +'T00:00:00Z&end='+ start +'T23:59:00Z&token=99b414f1-e5d5-4e46-9f2c-2c806b3efb9e',
                    type: 'GET',
                    dataType: 'text',
                    //dataType: 'jsonp',
                    crossDomain: true,
                    success: function(data) {
                        var calls = $.csv.toArrays(data);

                        var res = [];
                        var already = {};

    //console.log (calls);
                        for (var i = 0; i < calls.length; i++) {
                            var val = calls[i];
                            //console.log(val);
                            if (typeof(already['z'+val[3]]) == 'undefined') {
                                
                                res[val[4]] = val;
                                res[val[4]][9] = 0;
                                res[val[4]][10] = 0;
                                res[val[4]][11] = 0;
                                //res[Number(val[4].substr(val[4].length - 5))][1] = val[7];
                                already['z'+val[3]] = true;

                                if (val[1] == 'out') {

                                    res[val[4]][10] = Number(res[val[4]][10]) + Number(val[7]);

                                } else if (val[1] == 'in') { 
                                    res[val[4]][9] = Number(res[val[4]][9]) + Number(val[7]);
                                } else if (val[1] == 'missed') { 
                                    res[val[4]][11] = Number(res[val[4]][11] + 1);
                                }   
                            } else {
                                if (val[1] == 'out') {
                                    res[val[4]][10] = Number(res[val[4]][10]) + Number(val[7]);
                                } else if (val[1] == 'in') { 
                                    res[val[4]][9] = Number(res[val[4]][9]) + Number(val[7]);
                                } else if (val[1] == 'missed') { 
                                    res[val[4]][11] = Number(res[val[4]][11] + 1);
                                }     
                            }
                        }

    //console.log (res);
                        $('#tbl_calls tbody').empty();

                        for (variable in res) {
                            var minOut = Math.floor(res[variable][10]/60);
                            var secOut = res[variable][10] - minOut*60;

                            var minIn = Math.floor(res[variable][9]/60);
                            var secIn = res[variable][9] - minIn*60;

                            $('#tbl_calls').append('<tr><td>' + res[variable][3] + '</td><td style ="text-align: right">' + minOut + ' мин. ' + secOut + ' сек.' + '</td><td style ="text-align: right">' + minIn + ' мин. ' + secIn + ' сек.' + '</td><td style ="text-align: right">' + res[variable][11] + '</td></tr>');
                        };
                    }
                });    
            });
        </script>
    <!-- Optional JavaScript -->
    </body>
</html>

