<?php
header("Content-Type: application/json");
session_start();
$data = json_decode(file_get_contents('php://input'), true);
//若Session為空，將阻擋請求
if (empty($_SESSION['userId']) || empty($data)){
    if($data["do"] != "login"){
        $jsonData = ["msg" => "Please Login.", "success" => false];
        exit(json_encode($jsonData));
    }
}else{
    $userId = $_SESSION['$userId'];
}

//處理request，回傳json，帶success狀態
switch ($data["do"]) {
    // 登入
    case 'login':
        $loginId = $data["loginId"];
        $loginPw = $data["loginPw"];
        $jsonData = ["msg" => "Login Error.", "success" => false];
        
        //各用戶帳號密碼
        $userListArr = [
            "admin" => "admin",
            "user1" => "user1",
            "guest" => "guest"
        ];
        
        if(array_key_exists($loginId,$userListArr)){
            if($loginPw === $userListArr["$loginId"]){
                if(isset($_SESSION['userId'])){
                    session_destroy();
                }
                $_SESSION['userId'] = $loginId;
                $jsonData = ["msg" => "Login final.", "success" => true];
            }
        }
        echo json_encode($jsonData);
        break;
        
    // 登出
    case 'logout':
        $jsonData = ["msg" => "Logout Error.", "success" => false];
        if(isset($_SESSION['userId'])){
            session_destroy();
            $jsonData = ["msg" => "Logout final.", "success" => true];
        }
        echo json_encode($jsonData);
        break;
    
    default:
        $jsonData = ["msg" => "Todo event error.", "success" => false];
        echo json_encode($jsonData);
        break;
}
?>