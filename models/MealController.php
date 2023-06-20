<?php


/**
 * @OA\Info(
 *     title="Restaurant API",
 *     version="1.0"
 * )
 */
class MealController
{

    public function __construct($db)
    {
    }

    /**
     * @OA\Get(
     *     path="/Zadanie2_MyMenu/api/meals",
     *     summary="Get all meals",
     *     tags={"Meals"},
     *     @OA\Response(response=200,description="Success"),
     *     @OA\Response(response=404,description="Not Found"),
     * )
     */
    public function readMenu(PDO $db): void
    {
        $query = <<<SQL
    SELECT p.restaurant_fk, p.meal, p.price, p.date, r.name, p.image 
    FROM parsed p
    JOIN restaurants r on r.id = p.restaurant_fk
    ORDER BY 
        p.restaurant_fk ASC,
        CASE 
            WHEN date LIKE '%Pondelok%' THEN 1
            WHEN date LIKE '%Utorok%' THEN 2
            WHEN date LIKE '%Streda%' THEN 3
            WHEN date LIKE '%Štvrtok%' THEN 4
            WHEN date LIKE '%Piatok%' THEN 5
            WHEN date LIKE '%Sobota%' THEN 6
            WHEN date LIKE '%Nedeľa%' THEN 7
        END ASC;
    SQL;
        $stmt = $db->prepare($query);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($results) {
            foreach ($results as &$result) {
                if ($result['image']) {
                    $result['image'] = base64_encode($result['image']);
                }
            }
            unset($result);
            echo json_encode($results);
        } else {
            http_response_code(404);
            json_encode(["message" => "No meals found"]);
        }
    }


    /**
     * @OA\Get(
     *     path="/Zadanie2_MyMenu/api/meals/{day}",
     *     summary="Get all meals by day",
     *     tags={"Meals"},
     *     @OA\Parameter(
     *          name="day",
     *          in="path",
     *          description="Day of the week",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              enum={"Pondelok", "Utorok", "Streda", "Štvrtok", "Piatok", "Sobota", "Nedeľa"},
     *          ),
     *     ),
     *     @OA\Response(response=200,description="Success"),
     *     @OA\Response(response=404,description="Not Found"),
     * )
     */
    public function readMenuByDay($db, $day): void
    {
        $query = <<<SQL
        SELECT p.restaurant_fk, p.meal, p.price, p.date, r.name, p.image 
        FROM parsed p
        JOIN restaurants r on r.id = p.restaurant_fk
        WHERE date LIKE :day
        ORDER BY restaurant_fk ASC;
        SQL;

        $stmt = $db->prepare($query);
        $dayWithPercent = "%" . $day . "%";
        $stmt->bindParam(':day', $dayWithPercent);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($results) {
            foreach ($results as &$result) {
                if ($result['image']) {
                    $result['image'] = base64_encode($result['image']);
                }
            }
            echo json_encode($results);
        } else {
            http_response_code(404);
            json_encode(["message" => "No meals found"]);
        }
    }


    /**
     * @OA\Post(
     *   path="/Zadanie2_MyMenu/api/meals",
     *   tags={"Meals"},
     *   summary="Create meal for the week",
     *   @OA\RequestBody(
     *     required=true,
     *     description="Meal details",
     *     @OA\JsonContent(
     *       type="object",
     *       required={"restaurant_fk", "meal", "price"},
     *       @OA\Property(
     *         property="price",
     *         type="integer",
     *         description="Price of the meal"
     *       ),
     *       @OA\Property(
     *         property="meal",
     *         type="string",
     *         description="Name of the meal"
     *       ),
     *       @OA\Property(
     *         property="restaurant_fk",
     *         type="integer",
     *         description="Id of the restaurant"
     *       )
     *     )
     *   ),
     *   @OA\Response(response="201", description="Created"),
     *   @OA\Response(response="400", description="Bad request")
     * )
     */
    public function createMealForTheWeek($db, $data): void
    {
        $days = array('Pondelok', 'Utorok', 'Streda', 'Štvrtok', 'Piatok');
        foreach ($days as $day) {
            $stmt = $db->prepare('INSERT INTO parsed (restaurant_fk, meal, price, date) VALUES (:restaurant_fk, :meal, :price, :date);');
            $stmt->bindParam(':restaurant_fk', $data['restaurant_fk']);
            $stmt->bindParam(':meal', $data['meal']);
            $stmt->bindParam(':price', $data['price']);
            $stmt->bindParam(':date', $day);
            $stmt->execute();
            if ($stmt->rowCount() === 0) {
                http_response_code(400);
                echo json_encode(array('error' => 'Data not created'));
                return;
            }
        }
        http_response_code(201);
        echo json_encode(array('success' => 'Data created successfully', 'data' => $data));
    }


    /**
     * @OA\Put(
     *     path="/Zadanie2_MyMenu/api/meals/{id}",
     *     tags={"Meals"},
     *     summary="Update meal price",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="Id of the meal",
     *          @OA\Schema(
     *              type="integer",
     *          ),
     *     ),
     *     @OA\Response(response=200,description="Success"),
     *     @OA\Response(response="400", description="Bad request"),
     *     @OA\Response(response="404", description="Not found")
     *
     * )
     */
    public function updateMealPrice($db, $id, $data): void
    {
        if (!isset($data['price'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Price is required'));
            return;
        }
        $stmt = $db->prepare('UPDATE parsed SET price = :price WHERE id = :id;');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':price', $data['price']);
        $stmt->execute();
        if ($stmt->rowCount() === 0) {
            http_response_code(404);
            echo json_encode(array('error' => 'Data not found'));
            return;
        }
        echo json_encode(array('success' => 'Data updated successfully', 'data' => $data['price'], 'id' => $id));
    }


    /**
     * @OA\Delete (
     *     path="/Zadanie2_MyMenu/api/restaurant/{id}",
     *     tags={"Restaurants"},
     *     summary="Delete restaurant and all it's meals",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="Id of the restaurant",
     *          @OA\Schema(
     *              type="integer",
     *          ),
     *     ),
     *     @OA\Response(response=200,description="Success"),
     *     @OA\Response(response="404", description="Not found")
     * )
     */
    public function deleteRestaurant($db, $id): void
    {
        if (!isEmpty($id)) {
            echo json_encode(array('error' => 'Id not found'));
            http_response_code(404);
        } else {
            $stmt = $db->prepare('DELETE FROM restaurants WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            echo json_encode(array('success' => 'Data deleted successfully', 'id' => $id));
        }
    }
}