<?php include 'connect.php'; ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
  </head>
  <body>
    <?php
    if($_POST){
      $username = $_POST['username'];
      $password = $_POST['password'];
      $email = $_POST['email'];

      $stmt = $pdo->prepare("INSERT INTO basic_crud(username, password, email) VALUES('$username','$password','$email')");
      $stmt->execute();
      echo "<script>alert('Added Successfuly!'); window.location.href='index.php';</script>";
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
            <label for="">Username</label>
            <input type="text" name="username" class="form-control" placeholder="Enter Username" required>
            <label for="">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Enter Password" required>
            <label for="">Email</label>
            <input type="email" name="email" class="form-control" placeholder="Enter Email" required>
            <br>
            <button type="submit" class="btn btn-primary">Add</button>
          </form>
        </div>
      </div>
    </div>
  </body>
</html>
