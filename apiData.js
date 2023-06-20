async function getMeals() {
    const res = await axios.get('https://site71.webte.fei.stuba.sk/Zadanie2_MyMenu/api/meals');
    const items = res.data;
    const ol = document.getElementById('mealsData');


    let restaurant = items[0].restaurant_fk;
    let string = '<div class="col-md-4 vertical-line">'; // first
    string += '<h2 class="text-center mb-4"> ' + items[0].name + '</h2>';
    let date = items[0].date;
    string += '<div class="card mb-3">';
    string += '<div class="card-header">';
    string += '<h4> ' + date + '</h4></div>';
    string += '<ul class="list-group list-group-flush">';
    for (const item in items) {
        if (restaurant !== items[item].restaurant_fk) {
            restaurant = items[item].restaurant_fk;
            string += '</div></div><div class="col-md-4 vertical-line">';
            string += '<h2 class="text-center mb-4">' + items[item].name + '</h2>';
            date = items[item].date;
            string += '<div class="card mb-3">';
            string += '<div class="card-header">';
            string += '<h4> ' + date + '</h4></div>';
            string += '<ul class="list-group list-group-flush">';
        } else if (date !== items[item].date) {
            date = items[item].date;
            string += '</ul></div><div class="card mb-3">';
            string += '<div class="card-header">';
            string += '<h4> ' + date + '</h4></div>';
            string += '<ul class="list-group list-group-flush">';
        }
        let formattedNumber = (items[item].price).toFixed(2) + '€';
        if (items[item].image !== null) {
            string += '<li class="list-group-item d-flex justify-content-between">' +
                '<span>' + items[item].meal + '</span>' +
                '<span>' + formattedNumber + '</span>' +
                '<img style="width: 30%; height: auto;" src="data:image/png;base64,' + items[item].image + '" alt="jedlo">' +
                '</li>';
        } else {
            string += '<li class="list-group-item d-flex justify-content-between">' +
                '<span>' + items[item].meal + '</span>' +
                '<span>' + formattedNumber + '</span>' +
                '</li>';
        }

    }
    string += '</div>'; // last
    ol.innerHTML = string;
}


async function getMealByDay(day) {
    const res = await axios.get('https://site71.webte.fei.stuba.sk/Zadanie2_MyMenu/api/meals/' + day);
    const items = res.data;
    const container = document.getElementById('mealsData');


    let restaurant = items[0].restaurant_fk;
    let string = '<div class="col-md-4 vertical-line">'; // first
    string += '<h2 class="text-center mb-4"> ' + items[0].name + '</h2>';
    let date = items[0].date;
    string += '<div class="card mb-3">';
    string += '<div class="card-header">';
    string += '<h4> ' + date + '</h4></div>';
    string += '<ul class="list-group list-group-flush">';
    for (const item in items) {
        if (restaurant !== items[item].restaurant_fk) {
            restaurant = items[item].restaurant_fk;
            string += '</div></div><div class="col-md-4 vertical-line">';
            string += '<h2 class="text-center mb-4">' + items[item].name + '</h2>';
            date = items[item].date;
            string += '<div class="card mb-3">';
            string += '<div class="card-header">';
            string += '<h4> ' + date + '</h4></div>';
            string += '<ul class="list-group list-group-flush">';
        }
        let formattedNumber = (items[item].price).toFixed(2) + '€';
        if (items[item].image !== null) {
            string += '<li class="list-group-item d-flex justify-content-between">' +
                '<span>' + items[item].meal + '</span>' +
                '<span>' + formattedNumber + '</span>' +
                '<img style="width: 30%; height: auto;" src="data:image/png;base64,' + items[item].image + '" alt="jedlo">' +
                '</li>';
        } else {
            string += '<li class="list-group-item d-flex justify-content-between">' +
                '<span>' + items[item].meal + '</span>' +
                '<span>' + formattedNumber + '</span>' +
                '</li>';
        }
    }
    string += '</div>';
    container.innerHTML = string;
}


$(document).ready(function () {
    $("#updateMealForm").submit(function (event) {
        event.preventDefault();
        $("#updateMealModal").modal("hide");

        const mealId = $("#InputPriceMealID").val();
        const price = $("#InputPriceChange").val();

        $.ajax({
            url: "https://site71.webte.fei.stuba.sk/Zadanie2_MyMenu/api/meals/" + mealId,
            type: "PUT",
            data: JSON.stringify({price: price}),
            contentType: "application/json",
            success: function (response) {
                var toastEl = document.querySelector('.toast');
                var toastBody = toastEl.querySelector('.toast-body');
                var toast = new bootstrap.Toast(toastEl);
                toastBody.innerHTML = "Meal was updated successfully";
                toast.show();
                console.log(response);
                console.log("Success updating meal")
            },
            error: function (xhr, status, error) {
                console.log("Error: " + status + " - " + error);
                console.log("Error updating meal")
            }
        });
    });
});

$(document).ready(function () {
    $("#createMealForm").submit(function (event) {
        event.preventDefault();
        $("#createMealModal").modal("hide");

        const meal = $("#InputMeal").val();
        const price = $("#InputPrice").val();
        const restaurant_fk = $("#InputRestaurantCreate").val();


        $.ajax({
            url: "https://site71.webte.fei.stuba.sk/Zadanie2_MyMenu/api/meals",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({meal: meal, price: price, restaurant_fk: restaurant_fk}),
            success: function (response) {
                var toastEl = document.querySelector('.toast');
                var toastBody = toastEl.querySelector('.toast-body');
                var toast = new bootstrap.Toast(toastEl);
                toastBody.innerHTML = "Meal was created successfully";
                toast.show();
                console.log(response);
                console.log("Success creating meal")
            },
            error: function (xhr, status, error) {
                console.log("Error: " + status + " - " + error);
                console.log("Error creating meal")
            }
        });
    });
});

$(document).ready(function () {
    $("#deleteRestaurantForm").submit(function (event) {
        event.preventDefault();
        $("#deleteRestaurantModal").modal("hide");

        const restaurantId = $("#InputRestaurant").val();

        $.ajax({
            url: "https://site71.webte.fei.stuba.sk/Zadanie2_MyMenu/api/restaurant/" + restaurantId,
            type: "DELETE",
            contentType: "application/json",
            success: function (response) {
                var toastEl = document.querySelector('.toast');
                var toastBody = toastEl.querySelector('.toast-body');
                var toast = new bootstrap.Toast(toastEl);
                toastBody.innerHTML = "Restaurant was deleted successfully";
                toast.show();
                console.log(response);
                console.log("Success deleting restaurant")
            },
            error: function (xhr, status, error) {

                console.log("Error: " + status + " - " + error);
                console.log("Error deleting restaurant")
            }
        });
    });
});




