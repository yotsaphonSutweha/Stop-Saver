<?php 

function request() {
    return \Symfony\Component\HttpFoundation\Request::createFromGlobals();
}
function addJourney($title, $description) {
    global $db;
    $ownerId = getUser();

    try {
        $stmt = $db->prepare("INSERT INTO bus (title, stopno,user_id) VALUES ('$title', '$description', '$ownerId')");
        return $stmt->execute();
    } catch (\Exception $e) {
        throw $e;
    }
}

function editBus($busId, $title, $description) {
    global $db;

    try {
        $stmt = $db->prepare("UPDATE bus SET title='$title', stopno='$description' WHERE id='$busId'");
        return $stmt->execute();
    } catch (\Exception $e) {
        throw $e;
    }
}

function deleteBus($id){
    global $db;
    
    try{
        $stmt = $db->prepare("DELETE from bus where id ='$id'");
        $stmt->execute();
        return true;
    }catch(\Exception $e){
        return false;
    }
}

function getBus($id) {
    global $db;
    
    try {
        $stmt = $db->prepare("SELECT * FROM bus WHERE id = '$id'");
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
        $x = getUser();
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
        $stmt = $db->prepare("SELECT * from user WHERE username = '$email'");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }catch(\Exception $e){
        throw $e;
    }
}

function createUser($email, $password){
    global $db;
    
    try{
        $stmt = $db->prepare("INSERT INTO user (username, password) VALUES('$email', '$password')");
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