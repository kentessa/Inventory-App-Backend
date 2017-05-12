<?php 

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

// Get all inventory items 
$app->get('/api/inventory', function(Request $request, Response $response) {
    $sql = "SELECT * FROM inventory";

    try {
        //Get db object
        $db = new db();

        //Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $inventory = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($inventory);
    } catch(PDOException $e){
        echo '{"error": {"text": ' . $e->getMessage().'}';
    }

});

// Get a single inventory item 
$app->get('/api/inventory/{swttag}', function(Request $request, Response $response) {
    $swttag = $request->getAttribute('swttag');

    $sql = "SELECT * FROM inventory WHERE swttag = $swttag";

    try {
        //Get db object
        $db = new db();

        //Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $inventory = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($inventory);
    } catch(PDOException $e){
        echo '{"error": {"text": ' . $e->getMessage().'}';
    }
});

// Add an inventory item 
$app->post('/api/inventory/add', function(Request $request, Response $response) {
    $swttag = $request->getParam('swttag');
    $serial = $request->getParam('serial');
    $type = $request->getParam('type');
    $model = $request->getParam('model');
    $description = $request->getParam('description');
    $buydate = $request->getParam('buydate');
    $warranty = $request->getParam('warranty');
    $location = $request->getParam('location');
    $prev_location = $request->getParam('prev_location');

    $sql = "INSERT INTO inventory (swttag,serial,type,model,description,buydate,warranty,location,prev_location) VALUES 
            (:swttag,:serial,:type,:model,:description,:buydate,:warranty,:location,:prev_location)";

    try {
        //Get db object
        $db = new db();

        //Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':swttag', $swttag);
        $stmt->bindParam(':serial', $serial);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':model', $model);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':buydate', $buydate);
        $stmt->bindParam(':warranty', $warranty);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':prev_location', $prev_location);

        $stmt->execute();

        echo '{"notice": {"text": "item added"}';

    } catch(PDOException $e){
        echo '{"error": {"text": ' . $e->getMessage().'}';
    }
});

// Update an inventory item 
$app->put('/api/inventory/update/{swttag}', function(Request $request, Response $response) {
    $swttag = $request->getAttribute('swttag');
    $serial = $request->getParam('serial');
    $type = $request->getParam('type');
    $model = $request->getParam('model');
    $description = $request->getParam('description');
    $buydate = $request->getParam('buydate');
    $warranty = $request->getParam('warranty');
    $location = $request->getParam('location');
    $prev_location = $request->getParam('prev_location');

    $sql = "UPDATE inventory SET 
                 serial         = :serial,
                 type           = :type,
                 model          = :model,
                 description    = :description,
                 buydate        = :buydate,
                 warranty       = :warranty,
                 location       = :location,
                 prev_location  = :prev_location
            WHERE swttag = $swttag";
    try {
        //Get db object
        $db = new db();

        //Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':serial', $serial);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':model', $model);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':buydate', $buydate);
        $stmt->bindParam(':warranty', $warranty);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':prev_location', $prev_location);

        $stmt->execute();

        echo '{"notice": {"text": "item updated"}';

    } catch(PDOException $e){
        echo '{"error": {"text": ' . $e->getMessage().'}';
    }
});

// Delete an inventory item 
$app->delete('/api/inventory/delete/{swttag}', function(Request $request, Response $response) {
    $swttag = $request->getAttribute('swttag');

    $sql = "DELETE FROM inventory WHERE swttag = $swttag";

    try {
        //Get db object
        $db = new db();

        //Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);
        $stmt->execute();
        $db = null;

        echo '{"notice": {"text": "item deleted"}';

    } catch(PDOException $e){
        echo '{"error": {"text": ' . $e->getMessage().'}';
    }
});