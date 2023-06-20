<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors,', 1);
error_reporting(E_ALL);

function eat($html, $restaurant, $db): void
{
    $dom = new DOMDocument();
    $dom->loadHTML($html, LIBXML_NOERROR | LIBXML_NOWARNING);
    $xpath = new DOMXPath($dom);
    $brands = $xpath->query('//div[@class="tab-content weak-menu"]//h4');
    $description = $xpath->query('//div[@class="tab-content weak-menu"]//div[@class="menu-description"]/p');
    $prices = $xpath->query('//div[@class="tab-content weak-menu"]//span[@class="price"]/text()');
    $dayDates = $xpath->query('//div[@class="tabs clearfix"]//a');
    $image = $xpath->query('//div[@class="tab-content weak-menu"]//div[@class="menu-thumbnail"]/img');

    $imagesData = [];
    foreach ($image as $img) {
        $imagesData[] = file_get_contents($img->getAttribute('src'));
    }


    $names = [];

    foreach ($brands as $key => $value1) {
        if (isset($description[$key])) {
            $value2 = $description[$key];
            $names[] = $value1->nodeValue . " " . $value2->nodeValue;
        }
    }



    $formattedPrices = [];

    foreach ($prices as $price) {
        $formattedPrices[] = changePrice($price->nodeValue);
    }


    $id = restaurantExists($restaurant, $db);

    if ($id === null) {
        $query = <<<SQL
        INSERT INTO restaurants (name) VALUES (?);
        SQL;
        $stmt = $db->prepare($query);
        $stmt->execute([$restaurant]);
        $id = $db->lastInsertId();
    }


    for ($i = 0; $i < $brands->length; $i++) {
        $time = intdiv($i, 9);
        if (mealExists($names[$i], $dayDates->item($time)->nodeValue, $db)) {
            continue;
        }
        $query = <<<SQL
        INSERT INTO parsed (restaurant_fk, meal, price, date, image)
        VALUES (?, ?, ?, ?, ?);
        SQL;
        $stmt = $db->prepare($query);
        $stmt->execute([$id, $names[$i], $formattedPrices[$i], $dayDates->item($time)->nodeValue, $imagesData[$i]]);
    }

}

function fiitFood($html, $restaurant, $db): void
{
    $dom = new DOMDocument();
    $dom->loadHTML($html, LIBXML_NOERROR | LIBXML_NOWARNING);
    $xpath = new DOMXPath($dom);
    $brands = $xpath->query('//div[@id="fiit-food"]//ul[@class="daily-offer"]/li/ul[@class="day-offer"]/li/span[@class="brand"]');
    $description = $xpath->query('//div[@id="fiit-food"]//ul[@class="daily-offer"]/li/ul[@class="day-offer"]/li/text()');
    $prices = $xpath->query('//div[@id="fiit-food"]//ul[@class="daily-offer"]/li/ul[@class="day-offer"]/li/span[@class="brand price"]');
    $dayDates = $xpath->query('//div[@id="fiit-food"]//ul[@class="daily-offer"]/li/span[@class="day-title"]');

    $names = [];

    foreach ($brands as $key => $value1) {
        if (isset($description[$key])) {
            $value2 = $description[$key];
            $names[] = $value1->nodeValue . " " . $value2->nodeValue;
        }
    }

    $formattedPrices = [];

    foreach ($prices as $price) {
        $formattedPrices[] = changePrice($price->nodeValue);
    }
    $id = restaurantExists($restaurant, $db);

    if ($id === null) {
        $query = <<<SQL
        INSERT INTO restaurants (name) VALUES (?);
        SQL;
        $stmt = $db->prepare($query);
        $stmt->execute([$restaurant]);
        $id = $db->lastInsertId();
    }

    for ($i = 0; $i < $brands->length; $i++) {
        $time = intdiv($i, 4);
        if (mealExists($names[$i], $dayDates->item($time)->nodeValue, $db)) {
            continue;
        }
        $query = <<<SQL
        INSERT INTO parsed (restaurant_fk, meal, price, date)
        VALUES (?, ?, ?, ?);
        SQL;
        $stmt = $db->prepare($query);
        $stmt->execute([$id, $names[$i], $formattedPrices[$i], $dayDates->item($time)->nodeValue]);
    }


}

function venza($html, $restaurant, $db): void
{
    $dom = new DOMDocument();
    $dom->loadHTML($html, LIBXML_NOERROR | LIBXML_NOWARNING);
    $xpath = new DOMXPath($dom);
    $brands = $xpath->query('//div[@id="pills-tabContent"]//div[@class="menubar"]//h5[@class="mb-4 cursive-title primary"]');
    $description = $xpath->query('//div[@id="pills-tabContent"]//div[@class="menubar"]//div[@class="leftbar"]/h5');
    $prices = $xpath->query('//div[@id="pills-tabContent"]//div[@class="menubar"]//div[@class="rightbar d-flex align-items-center"]/h5');
    $dayDates = $xpath->query('//ul[@id="pills-tab"]/li[@class="nav-item"]/button');



    $formattedPrices = [];

    foreach ($prices as $price) {
        $formattedPrices[] = changePrice($price->nodeValue);
    }

    $id = restaurantExists($restaurant, $db);

    if ($id === null) {
        $query = <<<SQL
        INSERT INTO restaurants (name) VALUES (?);
        SQL;
        $stmt = $db->prepare($query);
        $stmt->execute([$restaurant]);
        $id = $db->lastInsertId();
    }




    for ($i = 0; $i < $description->length; $i++) {
        $time = intdiv($i, 10);
        if (mealExists($description->item($i)->nodeValue, $dayDates->item($time)->nodeValue, $db)) {
            continue;
        }
        $query = <<<SQL
        INSERT INTO parsed (restaurant_fk, meal, price, date)
        VALUES (?, ?, ?, ?);
        SQL;
        $stmt = $db->prepare($query);
        $stmt->execute([$id, $description->item($i)->nodeValue, $formattedPrices[$i], $dayDates->item($time)->nodeValue]);
    }
}

function changePrice($price): ?string
{
    if (preg_match('/^([\d,]{4,})/', $price, $matches)) {
        return str_replace(",", ".", $matches[0]);
    }
    return "1.00";
}

function restaurantExists($restaurant, $db) {
    $query = <<<SQL
        SELECT * FROM restaurants WHERE name = ?;
        SQL;
    $stmt = $db->prepare($query);
    $stmt->execute([$restaurant]);
    $result = $stmt->fetch();
    if ($result) {
        return $result['id'];
    }
    return null;
}

function mealExists($meal, $date, $db) : bool{
    if ($meal === null || $date === null) {
        return true;
    }

    $query = <<<SQL
        SELECT * FROM parsed WHERE meal = ? AND date = ?;
        SQL;
    $stmt = $db->prepare($query);
    $stmt->execute([$meal, $date]);
    $result = $stmt->fetch();
    if ($result) {
        return true;
    }
    return false;
}
