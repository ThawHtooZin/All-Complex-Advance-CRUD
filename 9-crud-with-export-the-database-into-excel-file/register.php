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
  <body>
    <?php
    if($_POST){
      if(empty($_POST['username']) || empty($_POST['email']) || empty($_POST['password'])){
        if(empty($_POST['username'])){
          $usererror = "The username field is required";
        }
        if(empty($_POST['email'])){
          $emailerror = "The email field is required";
        }
        if(empty($_POST['password'])){
          $passerror = "The password field is required";
        }
      }else{
        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $stmt = $pdo->prepare("INSERT INTO advance2_crud(username, email, password,role) VALUES('$username', '$email', '$password', 'user')");
        $data = $stmt->execute();
        if($data){
          echo "<script>alert('registered successfully!'); window.location.href='login.php'</script>";
        }
      }
    }
    ?>
    <?php include 'navbar.php'; ?>
    <div class="container mt-5">
      <div class="card">
        <div class="card-header">
          <h1>Register an account</h1>
        </div>
        <div class="card-body">
          <form class="" action="register.php" method="post" autocomplete="off">
            <input type="hidden" name="_token" value="<?php echo $_SESSION['_token']; ?>">
            <label for="">Username</label>
            <input type="text" name="username" class="form-control" placeholder="Enter the Username">
            <p class="text-center"><?php if(!empty($usererror)){echo $usererror;} ?></p>
            <label for="">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Enter the Password">
            <p class="text-center"><?php if(!empty($passerror)){echo $passerror;} ?></p>
            <label for="">Email</label>
            <input type="email" name="email" class="form-control" placeholder="Enter the Email">
            <p class="text-center"><?php if(!empty($emailerror)){echo $emailerror;} ?></p>
        </div>
        <div class="form-control">
          <div class="row container w-50 me-auto ms-auto">
            <button type="submit" class="btn btn-primary">Register</button>
          </div>
        </div>
      </form>
      </div>
    </div>
  </body>
</html>
