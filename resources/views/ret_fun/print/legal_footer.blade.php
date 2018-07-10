<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        body{
            padding: 0 69px 0 59px;
        }
    </style>
    <script>
        function pagination()
        {
            var vars = {};
            var x = document.location.search.substring(1).split('&');

            for (var i in x)
            {
                    var z = x[i].split('=', 2);
                    vars[z[0]] = unescape(z[1]);
            }

            var x = ['frompage','topage','page','webpage','section','subsection','subsubsection'];
            for (var i in x)
            {
                    var y = document.getElementsByClassName(x[i]);

                    for (var j = 0; j < y.length; ++j)
                    {
                        y[j].textContent = 'Página ' +vars[x[i]]+' de '+vars.topage;
                    }
            }
        }
    </script>
</head>
<body id="pdf-footer" onload="pagination()">
    <div style="height:60px;">
        <hr>
        <div style="float:left; font-family:sans-serif; font-size:14px;">
            <span>PLATAFORMA VIRTUAL DE TRÁMITES - MUSERPOL &nbsp;</span>
        </div>
        <div style="float:right; font-family:sans-serif; font-size:14px;">
            <span class="page"></span>
        </div>
    </div>
</body>
</html>
