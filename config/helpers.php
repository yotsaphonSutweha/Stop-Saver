<?php
    // Making request using Synfony HTTPFoundation package. 
    function request() {
        return \Symfony\Component\HttpFoundation\Request::createFromGlobals();
    }
    
    // Removing Special Characters from Strings to Prevent Cross Side Scripting
    function sanitizeString($string) {
        return preg_replace("/[^\w\s@.!?]/", "", $string);
    }
    
    // Create bus journeys, inserting user inputs into database.
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
    
    // Updating the details of bus, the details synchronizes with the the updated stop no.
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
    
    // Delete the indicated stop no.
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
    
    // Getting the bus Id used for editting the bus.
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
    
    
    // Getting a list of all of the users buses they have saved.
    function getAllBuses() {
        global $db;
        
        try {
            $user = getUser();
            $stmt = $db->prepare("SELECT * FROM bus WHERE user_id='$user'");
            $stmt->execute();
            return $stmt->fetchAll();
        } catch(\Exception $e) {
            $response = \Symfony\Component\HttpFoundation\Response::create(null, \Symfony\Component\HttpFoundation\Response::HTTP_FOUND,['Location' => '/add.php']);
            $response->send();
            exit;
        }
        
    }
    
    // Getting the users email, this function is used when signing up a new user and when a user is logging in.
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
    
    // Creates a new user account.
    function createUser($email, $password){
        global $db;
        
        try{
            $stmt = $db->prepare("INSERT INTO user (username, password) VALUES(:email, :password)");
            $stmt->bindParam(':email', $email);
            $email = sanitizeString($email);
            $stmt->bindParam(':password', $password);
            $stmt->execute();
            return getUserEmail($username);
        }catch(\Exception $e){
            throw $e;
        }
    }
    
    // Handles redirect operations on the website.
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
    
    // Authenticating user before logging in and verifying the users access token.
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
    
    // Getting the current users id.
    function getUser() {
        return decodeJwt('user');
    }
    
    // Handles unauthenticated users or users that are not logged in, redirects them back to the login page.
    function requireAuth(){
        if(!userAuth()){
            $accessToken = new \Symfony\Component\HttpFoundation\Cookie("access_token", "Expired", time()-(60 * 60 * 24 * 30), '/', getenv('DOMAIN'));
            redirect('/login.php', ['cookies' => [$accessToken]]);
        }
    }
    
    // Decoding json web tokens.
    function decodeJwt($prop = null) {
        \Firebase\JWT\JWT::$leeway = 1;
        $jwt = \Firebase\JWT\JWT::decode(
            request()->cookies->get('access_token'),
            getenv('KEY'),
            ['HS256']
        );
        
        return $prop === null ? $jwt : $jwt->$prop;
    }
    
    // Handles login sessions, indicating where web token was issued by, user the web token is for and when the web token expires
    // The iss is the URL that issues the web token, the users id is also stored in the JSON object. The exp is the time when the token expires.
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
?>