<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Swagger UI</title>
    <link rel="stylesheet" type="text/css" href="../swagger-ui/dist/swagger-ui.css" />
    <link rel="stylesheet" type="text/css" href="../swagger-ui/dist/index.css" />
    <link rel="icon" type="image/png" href="../swagger-ui/dist/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="../swagger-ui/dist/favicon-16x16.png" sizes="16x16" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
            crossorigin="anonymous"></script>
</head>
<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/Zadanie2_MyMenu/index.php"><h3>Domov</h3></a>
            <div class="navbar-nav ms-auto">
                <a class="navbar-brand" href="/Zadanie2_MyMenu/documentation/index.php"><h3>Swagger</h3></a>
            </div>
        </div>
    </nav>
</header>

<body>
<div id="swagger-ui"></div>
<script src="../swagger-ui/dist/swagger-ui-bundle.js" charset="UTF-8"> </script>
<script src="../swagger-ui/dist/swagger-ui-standalone-preset.js" charset="UTF-8"> </script>
<script>
    window.onload = function() {
        //<editor-fold desc="Changeable Configuration Block">

        // the following lines will be replaced by docker/configurator, when it runs in a docker-container
        window.ui = SwaggerUIBundle({
            url: "https://site71.webte.fei.stuba.sk/Zadanie2_MyMenu/documentation/doc.php",
            dom_id: '#swagger-ui',
            deepLinking: true,
            presets: [
                SwaggerUIBundle.presets.apis,
                SwaggerUIStandalonePreset
            ],
            plugins: [
                SwaggerUIBundle.plugins.DownloadUrl
            ],
            layout: "StandaloneLayout"
        });

        //</editor-fold>
    };
</script>
</body>
</html>

