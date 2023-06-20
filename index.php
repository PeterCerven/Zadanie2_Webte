<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors,', 1);
error_reporting(E_ALL);

session_start();


$db = require_once "utility/config.php";
require_once "actions/showdata.php";


?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Menu</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
              integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD"
              crossorigin="anonymous">
        <link rel="stylesheet" href="/Zadanie2_MyMenu/styles.css">

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
                    <button type="button" class="me-3" data-bs-toggle="modal" data-bs-target="#updateMealModal">Zmeň
                        cenu
                    </button>
                    <button type="button" class="me-3" data-bs-toggle="modal" data-bs-target="#deleteRestaurantModal">
                        Vymaž
                        reštauráciu
                    </button>
                    <button type="button" class="me-3" data-bs-toggle="modal" data-bs-target="#createMealModal">Pridaj
                        jedlo
                    </button>
                    <button type="button" class="me-3">
                        <a href="actions/download.php" class="text-decoration-none text-black">Stiahni</a>
                    </button>
                    <button type="button" class="me-3">
                        <a href="actions/parse.php" class="text-decoration-none text-black">Rozparsuj</a>
                    </button>
                    <button type="button" class="me-3">
                        <a href="actions/delete.php" class="text-decoration-none text-black">Vymaž</a>
                    </button>
                    <a class="navbar-brand" href="/Zadanie2_MyMenu/documentation/index.php"><h3>Swagger</h3></a>
                </div>
            </div>
        </nav>
    </header>

<body>
<main>
    <div class="container">
        <h1 class="text-center">Menu</h1>
        <div class="d-flex justify-content-center">
            <button type="button" class='btn btn-primary btn-lg me-3' onclick="getMeals()">Celý týždeň</button>
            <button class='btn btn-primary btn-lg me-3' onclick="getMealByDay(this.value)" value="Pondelok">Pondelok
            </button>
            <button class='btn btn-primary btn-lg me-3' onclick="getMealByDay(this.value)" value="Utorok">Utorok
            </button>
            <button class='btn btn-primary btn-lg me-3' onclick="getMealByDay(this.value)" value="Streda">Streda
            </button>
            <button class='btn btn-primary btn-lg me-3' onclick="getMealByDay(this.value)" value="Štvrtok">Štvrtok
            </button>
            <button class='btn btn-primary btn-lg me-3' onclick="getMealByDay(this.value)" value="Piatok">Piatok
            </button>
        </div>
        <div class="container">
            <div class="row" id="mealsData">

            </div>
        </div>
        <div class="position-fixed top-0 start-50 translate-middle-x" style="z-index: 11">
            <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <strong class="me-auto">Toast</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">

                </div>
            </div>
        </div>
        <div class="modal fade" id="updateMealModal" tabindex="-1" aria-labelledby="updateMealModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateMealModalLabel">Zmeň cenu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="updateMealForm" class="row g-3" action="" method="post">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="InputPriceMealID" class="form-label">Vyber jedlo:</label>
                                <select name="priceMealID" class="form-control" id="InputPriceMealID" required>
                                    <?php showMealID($db) ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="InputPriceChange" class="form-label">Cena:</label>
                                <input type="number" name="price" class="form-control" id="InputPriceChange" step="0.01"
                                       required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zavri</button>
                            <button type="submit" name="updateMeal" class="btn btn-primary">Zmeň cenu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="deleteRestaurantModal" tabindex="-1" aria-labelledby="deleteRestaurantModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteRestaurantModalLabel">Vymaž reštauráciu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="deleteRestaurantForm" class="row g-3" action="" method="post">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="InputRestaurant" class="form-label">Vyber reštauráciu:</label>
                                <select name="restaurant_id" class="form-control" id="InputRestaurant" required>
                                    <?php showRestaurantID($db)  ?>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zavri</button>
                            <button type="submit" name="deleteRestaurant" class="btn btn-primary">Vymaž reštauráciu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="createMealModal" tabindex="-1" aria-labelledby="createMealModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createMealModalLabel">Pridaj jedlo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="createMealForm" class="row g-3" action="" method="post">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="InputRestaurantCreate" class="form-label">Vyber reštauráciu:</label>
                                <select name="restaurant_id" class="form-control" id="InputRestaurantCreate" required>
                                    <?php showRestaurantID($db) ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="InputMeal" class="form-label">Názov:</label>
                                <input type="text" name="meal" class="form-control" id="InputMeal" required>
                            </div>
                            <div class="mb-3">
                                <label for="InputPrice" class="form-label">Cena:</label>
                                <input type="number" name="price" class="form-control" id="InputPrice" step="0.01"
                                       required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvor</button>
                            <button type="submit" name="createMeal" class="btn btn-primary">Pridaj jedlo</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        <?php if (isset($_SESSION['msg'])): ?>
        $(document).ready(function() {
            var toastEl = document.querySelector('.toast');
            var toast = new bootstrap.Toast(toastEl);
            $('.toast-body').html('Operation was successful.');
            toast.show();
        });
        <?php endif; ?>

        <?php unset($_SESSION['msg']); ?>
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.3.4/axios.min.js"></script>
    <script src="apiData.js"></script>
<?php require_once "footer.php";
