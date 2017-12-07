<?php 

function request() {
    return \Symfony\Component\HttpFoundation\Request::createFromGlobals();
}
function addJourney($title, $description) {
    global $db;
    $ownerId = getUser();

    try {
      
        $query = "INSERT INTO bus (title, stopno,user_id) VALUES ('$title', '$description', '$ownerId')";
        $stmt = $db->prepare($query);
        // $stmt->bindParam(':name', $title);
        // $stmt->bindParam(':description', $description);
        // $stmt->bindParam(':ownerId', $ownerId);
        return $stmt->execute();
    } catch (\Exception $e) {
        throw $e;
    }
}

function getAllBuses() {
    global $db;
    
    try {
        $buses = getUser();
        $x = getUser();
        $query = "SELECT * FROM bus WHERE user_id='$x'";
        $stmt = $db->prepare($query);
        // $stmt->bindParam(':user_id', getUser());

        $stmt->execute();
        return $stmt->fetchAll();
    } catch(\Exception $e) {
        $response = \Symfony\Component\HttpFoundation\Response::create(null, \Symfony\Component\HttpFoundation\Response::HTTP_FOUND,['Location' => '/add.php']);
        $response->send();
        exit;
        // throw $e;
    }
    
}


function getBus($id) {
    global $db;
    
    try {
        $query = "SELECT * FROM bus WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (\Exception $e) {
        throw $e;
    }
}

function editBus($busId, $title, $description) {
    global $db;

    try {
        $query = "UPDATE bus SET title='$title', stopno='$description' WHERE id='$busId'";
        $stmt = $db->prepare($query);
        // $stmt->bindParam(':name', $title);
        // $stmt->bindParam(':description', $description);
        // $stmt->bindParam(':bookId', $busId);
        return $stmt->execute();
    } catch (\Exception $e) {
        throw $e;
    }
}

function deleteBus($id){
    global $db;
    
    try{
        $query = "DELETE from bus where id =?";
        $stmt = $db->prepare($query);
        $stmt->execute([$id]);
        return true;
    }catch(\Exception $e){
        return false;
    }
}

function findUserByEmail($email){
    global $db;
    
    try{
        $query = "SELECT * from user WHERE username = '$email'";
        $stmt = $db->prepare($query);
        // $stmt->bindParam(':username', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }catch(\Exception $e){
        throw $e;
    }
}

function createUser($email, $password){
    global $db;
    
    try{
        $query = "INSERT INTO user (username, password) VALUES('$email', '$password')";
        $stmt = $db->prepare($query);
        // $stmt->bindParam(':username', $email);
        // $stmt->bindParam(':password', $password);
        $stmt->execute();
        return findUserByEmail($username);
    }catch(\Exception $e){
        throw $e;
    }
}


function redirect($path, $extra = []) {
    $response = \Symfony\Component\HttpFoundation\Response::create(null, \Symfony\Component\HttpFoundation\Response::HTTP_FOUND, ['Location' => $path]);
    if (key_exists('cookies', $extra)) {
        foreach ($extra['cookies'] as $cookie) {
            $response->headers->setCookie($cookie);
        }
    }
    $response->send();
    exit;
}

function isAuthenticated() {
    if(!request()->cookies->has('access_token')){
        return false;
    }
    
    try{
        \Firebase\JWT\JWT::$leeway = 1;
        \Firebase\JWT\JWT::decode(
            request()->cookies->get('access_token'),
            getenv('SECRET_KEY'),
            ['HS256']
            );
            return true;
    }catch(\Exception $e){
            return false;
    }
}

function getUser() {

        return decodeJwt('user');
    }


function requireAuth(){
    if(!isAuthenticated()){
        $accessToken = new \Symfony\Component\HttpFoundation\Cookie("access_token", "Expired",
        time()-3600, '/', getenv('COOKIE_DOMAIN'));
        redirect('/login.php', ['cookies' => [$accessToken]]);
    }
}

function isOwner($ownerId){
    if(!isAuthenticated()){
        return false;
    }
    
    try{
        $userId = decodeJwt('user');
    }catch(\Exception $e){
        return false;
    }
    
    return $ownerId == $userId;
}

function decodeJwt($prop = null) {
    \Firebase\JWT\JWT::$leeway = 1;
    $jwt = \Firebase\JWT\JWT::decode(
        request()->cookies->get('access_token'),
        getenv('SECRET_KEY'),
        ['HS256']
    );
    
    if ($prop === null) {
        return $jwt;
    }
    
    return $jwt->{$prop};
}