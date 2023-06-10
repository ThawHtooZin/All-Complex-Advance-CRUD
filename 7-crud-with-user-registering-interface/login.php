<?php session_start(); ?>
<?php include 'config/config.php'; ?>
<?php include 'config/connect.php'; ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
  <?php
  if($_POST){
    if(empty($_POST['email']) || empty($_POST['password'])){
      if(empty($_POST['email'])){
        $emailerror = "The email field is required";
      }
      if(empty($_POST['password'])){
        $passerror = "The password field is required";
      }
    }else{
      $email = $_POST['email'];
      $password = $_POST['password'];
      $stmt = $pdo->prepare("SELECT * FROM advance2_crud WHERE email='$email'");
      $stmt->execute();
      $data = $stmt->fetch(PDO::FETCH_ASSOC);
      if($password == $data['password']){
        if($data['role'] == "admin"){
          $_SESSION['logged_in'] = true;
          $_SESSION['username'] = $data['username'];
          $_SESSION['role'] = $data['role'];
          echo "<script>alert('welcome admin'); window.location.href='admin/index.php';</script>";
        }else{
          $_SESSION['username'] = $data['username'];
          echo "<script>alert('login success'); window.location.href='index.php';</script>";
        }
      }
    }
  }
  ?>
  <body>
    <?php include 'navbar.php'; ?>
    <div class="container mt-5">
      <div class="card">
        <div class="card-header">
          <h1>Login to your account</h1>
        </div>
        <div class="card-body">
          <form action="login.php" method="post" autocomplete="off">
            <input type="hidden" name="_token" value="<?php echo $_SESSION['_token']; ?>">
            <label for="">Email</label>
            <input type="email" name="email" class="form-control" placeholder="Enter your email">
            <p class="text-center"><?php if(!empty($emailerror)){echo $emailerror;} ?></p>
            <label for="">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Enter your password">
            <p class="text-center"><?php if(!empty($passerror)){echo $passerror;} ?></p>
        </div>
        <div class="card-footer">
          <div class="row container w-50 me-auto ms-auto">
            <button type="submit" class="btn btn-primary">Login</button>
          </div>
        </div>
      </form>
      </div>
    </div>
  </body>
</html>
