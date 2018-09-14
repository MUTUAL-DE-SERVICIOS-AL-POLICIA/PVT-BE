<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        /* body{
                    background:greenyellow;
                }
                img{
                    background:red;
                } */
    </style>
</head>

<body>
    <div style="height:60px;padding-top:10px; vertical-align:middle;">
        <div style="border-top: 1px solid #22292f;">
            <div style="width: 100%;margin:0;paddin:0; display:inline">
                <img src="data:image/png;base64, {{ $bar_code }}" style="height: 41px; width: 40%;" />
            </div>
            <div style="float:right; font-family:sans-serif; font-size:14px; text-align:right">
                <span>PLATAFORMA VIRTUAL DE TRÁMITES - MUSERPOL &nbsp;</span><br>
                </span>ÁREA LEGAL - FONDO DE RETIRO POLICIAL SOLIDARIO&nbsp;</span>
            </div>
        </div>
    </div>
</body>

</html>


{{--
<!DOCTYPE html>
<html>

<head>
    <script>
        function subst() {
      var vars = {};
      var query_strings_from_url = document.location.search.substring(1).split('&');
      for (var query_string in query_strings_from_url) {
          if (query_strings_from_url.hasOwnProperty(query_string)) {
              var temp_var = query_strings_from_url[query_string].split('=', 2);
              vars[temp_var[0]] = decodeURI(temp_var[1]);
          }
      }
      var css_selector_classes = ['page', 'frompage', 'topage', 'webpage', 'section', 'subsection', 'date', 'isodate', 'time', 'title', 'doctitle', 'sitepage', 'sitepages'];
      for (var css_class in css_selector_classes) {
          if (css_selector_classes.hasOwnProperty(css_class)) {
              var element = document.getElementsByClassName(css_selector_classes[css_class]);
              for (var j = 0; j < element.length; ++j) {
                  element[j].textContent = vars[css_selector_classes[css_class]];
              }
          }
      }
  }
    </script>
</head>

<body style="border:0; margin: 0;" onload="subst()">
    <table>
        <tr>
            <td class="section"></td>
            <td style="text-align:right">
                Page <span class="page"></span> of <span class="topage"></span>
            </td>
        </tr>
    </table>
</body>

</html> --}}