<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Saraph Games - Home of the Lexicon!</title>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
  <link rel="stylesheet" href="{{ cdn('css/style.css') }}" type="text/css">
  <link rel="stylesheet" href="{{ cdn('css/vue-style.css') }}" type="text/css">
  <script type="text/javascript">
    window.saraphgames_server_data = "{!! addslashes(json_encode($data)) !!}";
    window.csrf_token = "{{ csrf_token() }}"
    window.cdn_url = "{{ cdn('') }}";
  </script>
</head>
<body>
<div id="app"></div>
<script src="{{ cdn('js/app.js') }}"></script>
</body>
</html>
