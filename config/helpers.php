<?php 
//making request using Synfony HTTPFoundation package 
function request() {
    return \Symfony\Component\HttpFoundation\Request::createFromGlobals();
}

function sanitizeString($string) {
    return preg_replace("/[^\w\s@.]/", "", $string);
}
//create bus journeys, inserting user inuts into database
function addJourney($title, $stop_number) {
    global $db;
    $ownerId = getUser();

    try {
        $stmt = $db->prepare("INSERT INTO bus (title, stopno,user_id) VALUES (:title, :stop_number, :ownerId)");
        $stmt->bindParam(':title', $title);
        $title = sanitizeString($title);
        $stmt->bindParam(':stop_number', $stop_number);
        $stop_number = sanitizeString($stop_number);
        $stmt->bindParam(':ownerId', $ownerId);
        return $stmt->execute(); 
    } catch (\Exception $e) {
        throw $e;
    }
}
//updating 
function editBus($busId, $title, $stop_number) {
    global $db;

    try {
        $stmt = $db->prepare("UPDATE bus SET title=:title, stopno='$stop_number' WHERE id=:busId");
        $stmt->bindParam(':title', $title);
        $title = sanitizeString($title);
        $stmt->bindParam(':busId', $busId);
        $busId = sanitizeString($busId);
        return $stmt->execute();
    } catch (\Exception $e) {
        throw $e;
    }
}

function deleteBus($id){
    global $db;
    
    try{
        $stmt = $db->prepare("DELETE from bus where id =:id");
        $stmt->bindParam(':id', $id);
        $id = sanitizeString($id);
        $stmt->execute();
        return true;
    }catch(\Exception $e){
        return false;
    }
}

function getBus($id) {
    global $db;
    
    try {
        $stmt = $db->prepare("SELECT * FROM bus WHERE id = :id");
         $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (\Exception $e) {
        throw $e;
    }
}

function getAllBuses() {
    global $db;
    
    try {
        $buses = getUser();
        $stmt = $db->prepare("SELECT * FROM bus WHERE user_id='$buses'");
        $stmt->execute();
        return $stmt->fetchAll();
    } catch(\Exception $e) {
        $response = \Symfony\Component\HttpFoundation\Response::create(null, \Symfony\Component\HttpFoundation\Response::HTTP_FOUND,['Location' => '/add.php']);
        $response->send();
        exit;
    }
    
}


function getUserEmail($email){
    global $db;
    
    try{
        $stmt = $db->prepare("SELECT * from user WHERE username = :email");
        $stmt->bindParam(':email', $email);
        $email = sanitizeString($email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }catch(\Exception $e){
        throw $e;
    }
}

function createUser($email, $password){
    global $db;
    
    try{
        $stmt = $db->prepare("INSERT INTO user (username, password) VALUES(:email, :password)");
        $stmt->bindParam(':email', $email);
        $email = sanitizeString($email);
        $stmt->bindParam(':password', $password);
        $password = sanitizeString($password);
        $stmt->execute();
        return getUserEmail($username);
    }catch(\Exception $e){
        throw $e;
    }
}


function redirect($path, $info = []) {
    $redirectTo = \Symfony\Component\HttpFoundation\Response::create(null, \Symfony\Component\HttpFoundation\Response::HTTP_FOUND, ['Location' => $path]);
    if (key_exists('cookies', $info)) {
        foreach ($info['cookies'] as $cookie) {
            $redirectTo->headers->setCookie($cookie);
        }
    }
    $redirectTo->send();
    exit;
}

function userAuth() {
    if(!request()->cookies->has('access_token')){
        return false;
    }
    
    try{
        \Firebase\JWT\JWT::$leeway = 1;
        \Firebase\JWT\JWT::decode(
            request()->cookies->get('access_token'),
            getenv('KEY'),
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
    if(!userAuth()){
        $accessToken = new \Symfony\Component\HttpFoundation\Cookie("access_token", "Expired",
        time()-3600, '/', getenv('DOMAIN'));
        redirect('/login.php', ['cookies' => [$accessToken]]);
    }
}

function decodeJwt($prop = null) {
    \Firebase\JWT\JWT::$leeway = 1;
    $jwt = \Firebase\JWT\JWT::decode(
        request()->cookies->get('access_token'),
        getenv('KEY'),
        ['HS256']
    );
    
    return $prop === null ? $jwt : $jwt->$prop;
}

function login($expTime) {
    redirect('/',
        [
            'cookies' => [
                new Symfony\Component\HttpFoundation\Cookie('access_token', 
                    \Firebase\JWT\JWT::encode(
                        [
                            'iss' => request()->getBaseUrl(),
                            'user' => getUserEmail(request()->get('email'))['id'],
                            'exp' => $expTime
                        ], 
                        getenv("KEY"), 'HS256'
                    ), 
                    $expTime, '/',  getenv('DOMAIN')
                )
            ]
        ]
    );
}