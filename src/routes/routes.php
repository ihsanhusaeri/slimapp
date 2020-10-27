<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use Slim\App;

$settings = require_once  __DIR__ ."/../config/settings.php";

$app = new App($settings);

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

// Get List Users
$app->get('/getListUser', function(Request $request, Response $response) {
    $keyword = $request->getParam('keyword', $default=null);

        if($keyword) {
            $sql = "SELECT * FROM user WHERE id LIKE '%$keyword%' OR email LIKE '%$keyword%'";
        } else {
            $sql = "SELECT * FROM user";
        }
    
        try{
            // Get DB Object
            $db = new db();
            // Connect
            $db = $db->connect();
    
            $stmt = $db->query($sql);
            $users = $stmt->fetchAll(PDO::FETCH_OBJ);
            $db = null;
            // echo json_encode($users);
            return $response->withStatus(200)->withJson(["success" => true, "message" => "Successfully get list user", "data" =>$users]);
        } catch(PDOException $e){
            return $response->withStatus(500)->withJson(["success" => false, "message" => "Internal server error", "data" =>[]]);

        }
});

// Get Single User
$app->get('/getUser', function(Request $request, Response $response){
    
    $keyword = $request->getParam('keyword', $default=null);

    $sql = "SELECT * FROM user WHERE id = '$keyword' OR email = '$keyword'";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $user = $stmt->fetch(PDO::FETCH_OBJ);
        if(!$user) {
            $user = [];
        }
        $db = null;
        return $response->withStatus(200)->withJson(["success" => true, "message" => "Successfully get user", "data" =>$user]);
    } catch(PDOException $e){
        return $response->withStatus(500)->withJson(["success" => false, "message" => "Internal server error", "data" =>[]]);
    }
});

// Get List Companies
$app->get('/getListCompany', function(Request $request, Response $response) {

    $keyword = $request->getParam('keyword', $default=null);

    if($keyword) {
        $sql = "SELECT * FROM company WHERE id LIKE '%$keyword%'";
    } else {
        $sql = "SELECT * FROM company";
    }

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $companies = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        // echo json_encode($companies);
        return $response->withStatus(200)->withJson(["success" => true, "message" => "Successfully get list company", "data" =>$companies]);
    } catch(PDOException $e){
        return $response->withStatus(500)->withJson(["success" => false, "message" => "Internal server error", "data" =>[]]);
    }
});

// Get Single Company
$app->get('/getCompany/{id:[0-9]+}', function(Request $request, Response $response){
    
    // $keyword = $request->getParam('keyword', $default=null);
    $id = $request->getAttribute('id');

    $sql = "SELECT * FROM company WHERE id = '$id'";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $company = $stmt->fetch(PDO::FETCH_OBJ);
        if(!$company) {
            $company = [];
        }
        $db = null;
        return $response->withStatus(200)->withJson(["success" => true, "message" => "Successfully get list company", "data" =>$company]);
    } catch(PDOException $e){
        return $response->withStatus(500)->withJson(["success" => false, "message" => "Internal server error", "data" =>[]]);
    }
});
// Add User
$app->post('/createUser', function(Request $request, Response $response){
    $id = $request->getParam('id');
    $first_name = $request->getParam('first_name');
    $last_name = $request->getParam('last_name');
    $email = $request->getParam('email');
    $account = $request->getParam('account');
    $company_id = $request->getParam('company_id');

    $sql = "INSERT INTO user (id, first_name,last_name,email,account,company_id) VALUES
    (:id,:first_name,:last_name,:email,:account,:company_id)";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name',  $last_name);
        $stmt->bindParam(':email',      $email);
        $stmt->bindParam(':account',       $account);
        $stmt->bindParam(':company_id',      $company_id);

        $stmt->execute();

        return $response->withStatus(200)->withJson(["success" => true, "message" => "User Successfully created", "data" =>[]]);
    } catch(PDOException $e){
        return $response->withStatus(500)->withJson(["success" => false, "message" => "Internal server error", "data" =>[]]);
    }
});
// Update User
$app->put('/updateUser/{id:[0-9]+}', function(Request $request, Response $response){
    $id = $request->getAttribute('id');
    $first_name = $request->getParam('first_name');
    $last_name = $request->getParam('last_name');
    $email = $request->getParam('email');
    $account = $request->getParam('account');
    $company_id = $request->getParam('company_id');

    $sql = "UPDATE user SET
                first_name=:first_name,
                last_name=:last_name,
                email=:email,
                account=:account,
                company_id=:company_id
            WHERE id=$id";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name',  $last_name);
        $stmt->bindParam(':email',      $email);
        $stmt->bindParam(':account',       $account);
        $stmt->bindParam(':company_id',      $company_id);

        $stmt->execute();

        return $response->withStatus(200)->withJson(["success" => true, "message" => "User Successfully updated", "data" =>[]]);
    } catch(PDOException $e){
        return $response->withStatus(500)->withJson(["success" => false, "message" => "Internal server error", "data" =>[]]);
    }
});
//DELETE USER
$app->delete('/deleteUser/{id:[0-9]+}', function(Request $request, Response $response){
    $id = $request->getAttribute('id');
    

    $sql = "DELETE FROM user WHERE id=$id";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->execute();

        return $response->withStatus(200)->withJson(["success" => true, "message" => "User Successfully deleted", "data" =>[]]);
    } catch(PDOException $e){
        return $response->withStatus(500)->withJson(["success" => false, "message" => "Internal server error", "data" =>[]]);
    }
});
// Add Company
$app->post('/createCompany', function(Request $request, Response $response){
    $id = $request->getParam('id');
    $name = $request->getParam('name');
    $address = $request->getParam('address');

    $sql = "INSERT INTO company (id,name,address) VALUES
    (:id,:name,:address)";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':address',  $address);
        
        $stmt->execute();

        return $response->withStatus(200)->withJson(["success" => true, "message" => "Company successfully created", "data" =>[]]);
    } catch(PDOException $e){
        return $response->withStatus(500)->withJson(["success" => false, "message" => "Internal server error", "data" =>[]]);
    }
});
// Update Company
$app->put('/updateCompany/{id:[0-9]+}', function(Request $request, Response $response){
    $id = $request->getAttribute('id');
    $name = $request->getParam('name');
    $address = $request->getParam('address');

    $sql = "UPDATE company  SET
                name=:name,
                address=:address
            WHERE id = $id";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':address',  $address);
        
        $stmt->execute();

        return $response->withStatus(200)->withJson(["success" => true, "message" => "Company successfully updated", "data" =>[]]);
    } catch(PDOException $e){
        return $response->withStatus(500)->withJson(["success" => false, "message" => "Internal server error", "data" =>[]]);
    }
});
//DELETE COMPANY
$app->delete('/deleteCompany/{id:[0-9]+}', function(Request $request, Response $response){
    $id = $request->getAttribute('id');
    

    $sql = "DELETE FROM company WHERE id=$id";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->execute();

        return $response->withStatus(200)->withJson(["success" => true, "message" => "Company successfully deleted", "data" =>[]]);
    } catch(PDOException $e){
        return $response->withStatus(500)->withJson(["success" => false, "message" => "Internal server error", "data" =>[]]);
    }
});
// Reimburse
$app->post('/reimburse', function(Request $request, Response $response){
    $id = $request->getParam('id');
    $type = $request->getParam('type');
    $user_id = $request->getParam('user_id');
    $amount = $request->getParam('amount');
    date_default_timezone_set('Asia/Jakarta');
    $t = time();
    $date = date("Y-m-d H:i:s",$t);
    $sql = "INSERT INTO transaction (id,type,user_id,amount,date) VALUES
    (:id,:type,:user_id,:amount,:date)";
    
    $sql_update = "UPDATE company_budget SET 
    amount = GREATEST(amount - :amount, 0)
    WHERE company_id=(SELECT(company_id) FROM user WHERE id=:user_id)";
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':user_id',  $user_id);
        $stmt->bindParam(':amount',  $amount);
        $stmt->bindParam(':date',  $date);
        
        $stmt->execute();

        //Update company budget
        $stmt_update = $db->prepare($sql_update);
    
        $stmt_update->bindParam(':amount',  $amount);
        $stmt_update->bindParam(':user_id',  $user_id);

        $stmt_update->execute();
        return $response->withStatus(200)->withJson(["success" => true, "message" => "Reimburse transaction added", "data" =>[]]);
    } catch(PDOException $e){
        return $response->withStatus(500)->withJson(["success" => false, "message" => "Internal server error", "data" =>[]]);
    }
});
// disburse
$app->post('/disburse', function(Request $request, Response $response){
    $id = $request->getParam('id');
    $type = $request->getParam('type');
    $user_id = $request->getParam('user_id');
    $amount = $request->getParam('amount');
    date_default_timezone_set('Asia/Jakarta');
    $t = time();
    $date = date("Y-m-d H:i:s",$t);
    $sql = "INSERT INTO transaction (id,type,user_id,amount,date) VALUES
    (:id,:type,:user_id,:amount,:date)";

    $sql_update = "UPDATE company_budget SET 
    amount = GREATEST(amount - :amount, 0)
    WHERE company_id=(SELECT(company_id) FROM user WHERE id=:user_id)";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':user_id',  $user_id);
        $stmt->bindParam(':amount',  $amount);
        $stmt->bindParam(':date',  $date);
        
        $stmt->execute();

        //Update company budget
        $stmt_update = $db->prepare($sql_update);
        
        $stmt_update->bindParam(':amount',  $amount);
        $stmt_update->bindParam(':user_id',  $user_id);

        $stmt_update->execute();

        return $response->withStatus(200)->withJson(["success" => true, "message" => "disburse transaction added", "data" =>[]]);
    } catch(PDOException $e){
        return $response->withStatus(500)->withJson(["success" => false, "message" => "Internal server error", "data" =>[]]);
    }
});

$app->post('/close', function(Request $request, Response $response){
    $id = $request->getParam('id');
    $type = $request->getParam('type');
    $user_id = $request->getParam('user_id');
    $amount = $request->getParam('amount');
    date_default_timezone_set('Asia/Jakarta');
    $t = time();
    $date = date("Y-m-d H:i:s",$t);
    $sql = "INSERT INTO transaction (id,type,user_id,amount,date) VALUES
    (:id,:type,:user_id,:amount,:date)";
    
    $sql_update = "UPDATE company_budget SET 
                        amount = GREATEST(amount + :amount, 0)
                   WHERE company_id=(SELECT(company_id) FROM user WHERE id=:user_id)";
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->prepare($sql);

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':user_id',  $user_id);
        $stmt->bindParam(':amount',  $amount);
        $stmt->bindParam(':date',  $date);
        
        $stmt->execute();
       
        //Update company budget
        $stmt_update = $db->prepare($sql_update);
        
        $stmt_update->bindParam(':amount',  $amount);
        $stmt_update->bindParam(':user_id',  $user_id);

        $stmt_update->execute();

        return $response->withStatus(200)->withJson(["success" => true, "message" => "Close transaction added", "data" =>[]]);
    } catch(PDOException $e){
        return $response->withStatus(500)->withJson(["success" => false, "message" => "Internal server error", "data" =>[]]);
    }
});

// Get Single Company Budget
$app->get('/getBudgetCompany/{id:[0-9]+}', function(Request $request, Response $response){
    
    // $keyword = $request->getParam('keyword', $default=null);
    $id = $request->getAttribute('id');

    $sql = "SELECT * FROM company_budget WHERE id = '$id'";

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $company_budget = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;
        return $response->withStatus(200)->withJson(["success" => true, "message" => "Successfully get company budget", "data" =>$company_budget]);
    } catch(PDOException $e){
        return $response->withStatus(500)->withJson(["success" => false, "message" => "Internal server error", "data" =>[]]);
    }
});
// GetListBudgetCompany
$app->get('/getListBudgetCompany', function(Request $request, Response $response) {

    $id = $request->getParam('id', $default=null);

    if($id) {
        $sql = "SELECT * FROM company_budget WHERE id LIKE '%$id%'";
    } else {
        $sql = "SELECT * FROM company_budget";
    }

    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $companyBudgets = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        // echo json_encode($companyBudgets);
        return $response->withStatus(200)->withJson(["success" => true, "message" => "Successfully get list company budget", "data" =>$companyBudgets]);
    } catch(PDOException $e){
        return $response->withStatus(500)->withJson(["success" => false, "message" => "Internal server error", "data" =>[]]);
    }
});

// Get Log transaction
$app->get('/getLogTransaction', function(Request $request, Response $response) {
    $sql = "SELECT 
                CONCAT(u.first_name, ' ', u.last_name) AS name, 
                u.account AS account_number, 
                c.name AS company_name, 
                t.type AS transaction_type, 
                t.date AS transaction_date, 
                t.amount, 
                cb.amount AS remaining_amount 
            FROM transaction t 
            INNER JOIN company c ON 
                (SELECT(u.company_id) FROM user u WHERE u.id=t.user_id) = c.id 
            INNER JOIN user u ON u.id=t.user_id 
            INNER JOIN company_budget cb ON c.id=cb.company_id";
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $log_transaction = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        // echo json_encode($log_transaction);
        return $response->withStatus(200)->withJson(["success" => true, "message" => "Successfully get list company budget", "data" =>$log_transaction]);
    } catch(PDOException $e){
        return $response->withStatus(500)->withJson(["success" => false, "message" => "Internal server error", "data" =>[]]);
    }
});

$middleware = require_once __DIR__."/../config/middleware.php";

$middleware($app);