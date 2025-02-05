<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Importación</title>

        <style>
          * {
              margin: 0;
              padding: 0;
              box-sizing: border-box;
          }
      
          html, body {
              background-color: #fff;
              color: #636b6f;
              font-family: 'Raleway', sans-serif;
              font-weight: 100;
              height: 100vh;
              overflow: auto;
          }
      
          .full-height {
              height: 100vh;
          }
      
          .flex-center {
              align-items: center;
              display: flex;
              justify-content: center;
          }
      
          .position-ref {
              position: relative;
          }
      
          .top-right {
              position: absolute;
              right: 10px;
              top: 18px;
          }
      
          .content {
              margin-top: 30px;
              text-align: center;
          }

          .content2 {
              margin-bottom: 30px;
              text-align: center;
          }
          .title {
              font-size: 60px;
              margin-bottom: 20px;
          }
      
          .links > a {
              color: #636b6f;
              padding: 0 25px;
              font-size: 12px;
              font-weight: 600;
              letter-spacing: .1rem;
              text-decoration: none;
              text-transform: uppercase;
          }
      
          .m-b-md {
              margin-bottom: 30px;
          }
      
          .container {
              display: flex;
              width: 100%;
              gap: 20px;
              padding: 0 20px;
              height: auto;
              align-items: center;
              justify-content: center;
              flex-wrap: wrap;
          }
      
          .column {
              flex: 1;
              display: flex;
              justify-content: center;
              align-items: center;
              min-width: 300px;
          }
      
          .form {
              display: flex;
              flex-direction: column;
              gap: 15px;
              width: 100%;
              max-width: 400px;
          }
      
          .form div {
              display: flex;
              flex-direction: column;
              gap: 5px;
          }
      
          label {
              font-weight: bold;
              margin-bottom: 5px;
          }
      
          input {
              padding: 10px;
              border: 1px solid #ccc;
              border-radius: 5px;
              width: 100%;
          }
      
          button {
              padding: 10px 20px;
              background-color: #007bff;
              color: white;
              border: none;
              border-radius: 5px;
              cursor: pointer;
              font-size: 16px;
              width: 100%;
              text-align: center;
          }
      
          button:hover {
              background-color: #0056b3;
          }

          .button {
              padding: 10px 20px;
              background-color: #007bff;
              color: white;
              border: none;
              border-radius: 10px;
              cursor: pointer;
              font-size: 16px;
              width: 100%;
              text-align: center;
              margin-top: 100px;
          }
      
          .button:hover {
              background-color: #0056b3;
          }
      
          table {
              width: 100%;
              max-width: 800px;
              margin: 20px auto;
              border-collapse: collapse;
              box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
          }
      
          th, td {
              padding: 12px 15px;
              border: 1px solid #ddd;
          }
      
          th {
              background-color: #319c34;
              color: white;
              font-size: 18px;
              text-align: center;
          }
      
          tr:nth-child(even) {
              background-color: #f2f2f2;
          }
      
          tr:hover {
              background-color: #ddd;
          }
      
          td {
              font-size: 16px;
          }
      
          caption {
              font-size: 24px;
              font-weight: bold;
              margin-bottom: 20px;
          }
      
          .sub_description {
              text-align: left;
          }
      
          .result {
              text-align: right;
          }
      
          @media (max-width: 768px) {
              .title {
                  font-size: 40px;
              }
      
              .container {
                  padding: 0 10px;
              }
      
              .column {
                  flex: 1 1 100%;
              }
      
              table {
                  font-size: 14px;
              }
      
              th, td {
                  padding: 8px 10px;
              }
          }
      
          @media (max-width: 480px) {
              .title {
                  font-size: 30px;
              }
      
              .form {
                  gap: 10px;
              }
      
              button {
                  font-size: 14px;
              }
          }
          .modal {
            display: none; 
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            justify-content: center;
            align-items: center;
          }

          .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            width: 300px;
          }

          .modal-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            margin: 5px;
            cursor: pointer;
          }

          .modal-button:hover {
            background-color: #45a049;
          }

          #cancelButton {
            background-color: #f44336;
          }

          #cancelButton:hover {
            background-color: #e53935;
          }
      </style>
      <script>
        function openModal(event) {
          event.preventDefault();
          document.getElementById('confirmationModal').style.display = 'flex';
        }

        document.getElementById('confirmButton').onclick = function() {
          document.getElementById('importForm').submit();
          document.getElementById('confirmationModal').style.display = 'none';
        };

        document.getElementById('cancelButton').onclick = function() {
          document.getElementById('confirmationModal').style.display = 'none';
        };
      </script>
    </head>
    <body>
        <div class="content">
            <div class="title m-b-md">
              @yield('title')
            </div>
        </div>

        @yield('notice1')

        <div class="container">
            @yield('content')
        </div>

        @yield('notice2')

    </body>
</html>
