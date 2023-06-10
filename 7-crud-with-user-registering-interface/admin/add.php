<?php session_start(); ?>
<?php include 'config/connect.php'; ?>
<?php include 'config/config.php'; ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" href="../../../bootstrap-5.0.2-dist/css/bootstrap.css">
  </head>
  <body>
    <?php
    if($_POST){
      if(empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])){
        if(empty($_POST['username'])){
          $usererror = "Username Required";
        }
        if(empty($_POST['password'])){
          $passerror = "Password Required";
        }
        if(empty($_POST['email'])){
          $emailerror = "Email Required";
        }
      }else{
        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $role = $_POST['role'];
        $stmt = $pdo->prepare("INSERT INTO advance2_crud(username,password,email,role) VALUES('$username', '$password', '$email','$role')");
        $stmt->execute();
        echo "<script>alert('Data Inserted Successfully');</script>";
      }
    }
    ?>
    <div class="container mt-5">
      <div class="card">
        <div class="card-header">
          <h1 class="d-inline">Addition Page</h1>
          <a href="index.php" class="btn btn-warning float-end">Back</a>
        </div>
        <div class="card-body">
          <form action="add.php" method="post">
            <input type="hidden" name="_token" value="<?php echo $_SESSION['_token']; ?>">
            <label for="">Username</label>
            <input type="text" name="username" class="form-control" placeholder="Enter Username" required>
            <p class="text-danger"><?php if(!empty($usererror)){ echo $usererror; } ?></p>
            <label for="">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Enter Password" required>
            <p class="text-danger"><?php if(!empty($passerror)){ echo $passerror; } ?></p>
            <label for="">Email</label>
            <input type="email" name="email" class="form-control" placeholder="Enter Email" required>
            <p class="text-danger"><?php if(!empty($emailerror)){ echo $emailerror; } ?></p>
            <label for="">Role</label>
            <select class="form-control" name="role">
              <option value="admin">admin</option>
              <option value="user">user</option>
            </select>
            <br>
            <button type="submit" class="btn btn-primary">Add</button>
          </form>
        </div>
      </div>
    </div>
  </body>
</html>
