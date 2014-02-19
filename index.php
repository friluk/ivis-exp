<!DOCTYPE html>
<html lang="de">
  <head>
    <title>Getting it to work!</title>
    <meta charset="UTF-8">
    <meta name=description content="">
    <meta name=viewport content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <style>
      .container-main {
        margin-top: 50px;
        width: 95%;
      }

      .key {
        color: blue;
      }

      .string {
        color: green;
      }

      .boolean {
        color: red;
      }
    </style>
  </head>
  <body>
    <div class="container container-main">      
      <div class="row">
        <div class="col-md-2">
          <section class="panel panel-default">
            <div class="panel-heading">
              <h1 class="panel-title">Controls</h1>
            </div>
            <div class="panel-body">
              <p><a href="clearsessions.php" class="btn btn-primary">Clear sessions</a></p>            
              <h2>Timelines</h2>
              <div class="list-group timelines">
                <a href="apicall.php?method=get&amp;call=mentions" class="list-group-item apilink">mentions</a>
                <a href="apicall.php?method=get&amp;call=user_timeline" class="list-group-item apilink">user timeline</a>
                <a href="apicall.php?method=get&amp;call=home_timeline" class="list-group-item apilink">home timeline</a>
                <a href="apicall.php?method=get&amp;call=retweets" class="list-group-item apilink">retweets</a>
              </div>
              <p><a href="start.php" class="btn btn-default btn-lg btn-primary">Go!</a></p>
            </div>
          </section>
        </div>
        <div class="col-md-10">
          <section class="panel panel-default">
            <div class="panel-heading">
              <h1 class="panel-title">Output</h1>
            </div>
            <div class="panel-body">
              <div class="text-center" id="loader" style="display:none"><img src="assets/images/ajax_loader.gif" alt="Loading"></div>
              <pre id="output"></pre>
            </div>
          </section>
        </div>
      </div>

      <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title" id="myModalLabel">API Return</h4>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

    </div>

    <!-- jQuery -->
    <script src="//code.jquery.com/jquery.js"></script>
    <!-- Bootstrap JavaScript -->
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
    <script>
      function syntaxHighlight(json) {
        if (typeof json != 'string') {
             json = JSON.stringify(json, undefined, 2);
        }
        json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
            var cls = 'number';
            if (/^"/.test(match)) {
                if (/:$/.test(match)) {
                    cls = 'key';
                } else {
                    cls = 'string';
                }
            } else if (/true|false/.test(match)) {
                cls = 'boolean';
            } else if (/null/.test(match)) {
                cls = 'null';
            }
            return '<span class="' + cls + '">' + match + '</span>';
        });
      }

      $(function(){

        var modal = $('#myModal'),
            modalbody = modal.find('.modal-body'),
            output = $('#output'),
            loader = $('#loader');

        function requestSuccessful(data, textStatus, jqXHR) {
          output.html(syntaxHighlight(data));
          output.show();
          loader.hide();
        }

        function requestError(jqXHR, textStatus, errorThrown) {
          modalbody.html("<strong>"+textStatus+"</strong>: "+errorThrown);
          modal.modal('show');
          output.show();
          loader.hide();
        }

        function apiclick (event){
          var $this = $(this);

          output.hide();
          loader.show();

          $.ajax($this.attr('href'), {
            success: requestSuccessful,
            error: requestError,
            type: 'GET',
            dataType: 'json'
          });

          event.preventDefault();
        }

        $('body').on('click', '.apilink', apiclick);
      });
    </script>
  </body>
</html>