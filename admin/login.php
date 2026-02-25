<?php
session_start();
include "../includes/koneksi.php";

if(isset($_POST['login'])){

    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $query = mysqli_query($conn, 
        "SELECT * FROM users 
         WHERE username='$username' 
         AND password='$password' 
         AND role='admin'"
    );

    $data = mysqli_fetch_assoc($query);

    if($data){
        $_SESSION['admin'] = true;
        $_SESSION['nama'] = $data['nama'];
        header("Location: ../pages/kas.php");
        exit;
    } else {
        echo "Login gagal!";
    }
}
?>

<form method="POST">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit" name="login">Login</button>
</form>