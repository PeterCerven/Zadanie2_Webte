<?php



function showDay($day, $id, $db): void
{
    $query = <<<SQL
        SELECT * FROM parsed p
        WHERE p.date like ? and p.restaurant_fk = ?
        SQL;

    $stmt = $db->prepare($query);
    $stmt->execute(['%' . $day . '%', $id]);
    $meals = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($meals as $meal) {
        echo "{$meal["name"]}  {$meal["price"]} {$meal["image"]} <br><hr>";
    }
}

function showWeek($id, $db): void
{
    $query = <<<SQL
        SELECT * FROM parsed p
        WHERE p.restaurant_fk = ?
        SQL;

    $stmt = $db->prepare($query);
    $stmt->execute([$id]);
    $meals = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $day = "";

    foreach ($meals as $meal) {
        if ($day !== $meal["date"]) {
            $day = $meal["date"];
            echo "<h4>{$day}</h4>";
        }
        echo "{$meal["name"]}  {$meal["price"]} {$meal["image"]} <br><hr>";
    }
}

function showMealID($db): void
{
    $query = <<<SQL
            SELECT * FROM parsed
            SQL;
    $stmt = $db->query($query);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $result) {
        echo "<option value='{$result["id"]}'>{$result["meal"]} {$result["price"]} € </option>";
    }
}

function showRestaurantID($db): void
{
    $query = <<<SQL
            SELECT * FROM restaurants
            SQL;
    $stmt = $db->query($query);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $result) {
        echo "<option value='{$result["id"]}'>{$result["name"]}</option>";
    }
}