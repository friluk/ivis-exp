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
    <script src="assets/js/processing-js/processing-1.4.1.min.js"></script>
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
              <p><a href="twitteroauth/clearsessions.php" class="btn btn-primary">Connect/Reconnect</a></p>
              <h3>Timelines</h3>
              <div class="list-group timelines">
                <a href="apicall.php?method=get&amp;call=mentions_timeline" data-proc-name="mentionsTimeline" class="list-group-item apilink">mentions</a>
                <a href="apicall.php?method=get&amp;call=user_timeline" data-proc-name="userTimeline" class="list-group-item apilink">user timeline</a>
                <a href="apicall.php?method=get&amp;call=home_timeline" data-proc-name="homeTimeline" class="list-group-item apilink">home timeline</a>
                <a href="apicall.php?method=get&amp;call=retweets_of_me" data-proc-name="retweetsOfMe" class="list-group-item apilink">retweets</a>
              </div>
              <h3>Tweets</h3>
              <div class="list-group tweets">              
                <form action="apicall.php" method="get" class="list-group-item api-form" data-proc-name="retweets">
                  <label class="sr-only" id="retweets">ID: </label>
                  <div class="input-group input-group-sm" id="retweets">
                    <input type="number" class="form-control" id="retweets" name="id" placeholder="Tweet ID">
                    <span class="input-group-btn">
                      <button type="submit" class="btn btn-primary">Go</button>
                    </span>
                  </div>
                  <input type="hidden" name="method" value="get">
                  <input type="hidden" name="call" value="retweets">
                </form>
              </div>
            </div>
          </section>
        </div>
        <div class="col-md-10">
          <div class="panel-group" id="output-group">
            <section class="panel panel-default">
              <div class="panel-heading">
                <h1 class="panel-title">
                  <a href="#json-output-wrapper" data-toggle="collapse" data-parent="#output-group">Output (JSON)</a>
                </h1>
              </div>
              <div class="panel-collapse collapse in" id="json-output-wrapper">
                <div class="panel-body">
                  <div class="text-center" id="loader" style="display:none"><img src="assets/images/ajax-loader.gif" alt="Loading"></div>
                  <pre id="output"></pre>
                </div>
              </div>
            </section>
            <section class="panel panel-default">
              <div class="panel-heading">
                <h1 class="panel-title">
                  <a href="#proc-output-wrapper" data-toggle="collapse" data-parent="#output-group">Output (Processing)</a>
                </h1>
              </div>
              <div class="panel-collapse collapse" id="proc-output-wrapper">
                <div class="panel-body">
                    <canvas id="processing-output"data-processing-sources="assets/processing/test.pde"></canvas>
                  </div>
                </div>
              </div>
            </section>
          </div>
        </div>
      </div>
    </div>

    <!-- jQuery -->
    <script src="//code.jquery.com/jquery.js"></script>
    <!-- Bootstrap JavaScript -->
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
    <script>

      ivis = {};

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

        var output = $('#output'),
            loader = $('#loader');

        function requestSuccessful(data, textStatus, jqXHR) {
          output.html(syntaxHighlight(data));
          output.show();
          loader.hide();
        }

        function requestError(jqXHR, textStatus, errorThrown) {
          output.html(textStatus + ': ' + errorThrown);
          output.show();
          loader.hide();
        }

        function apicall (url, procName) {
          $.ajax(url, {
            success: function(data, textStatus, jqXHR){
              ivis[procName] = data;
              ivis.currentProcName = procName;
              requestSuccessful(data, textStatus, jqXHR);
            },
            error: requestError,
            type: 'GET',
            dataType: 'json',
          });
        }

        function apiclick (event){
          var $this = $(this),
              procName = $this.data('proc-name') || false;

          output.hide();
          loader.show();

          apicall($this.attr('href'), procName);

          event.preventDefault();
        }

        function apisubmit (event) {
          var $this = $(this),
              formData = $this.serialize(),
              url = $this.attr('action') + '?' + formData,
              procName = $this.data('proc-name') || false;
          output.hide();
          loader.show();
          apicall(url, procName);

          event.preventDefault();
        }

        $('body').on('click', '.apilink', apiclick);
        $('body').on('submit', '.api-form', apisubmit);
      });
    </script>
  </body>
</html>