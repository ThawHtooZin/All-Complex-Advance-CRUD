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
    $id = $_GET['id'];
    if($_POST){
      $username = $_POST['username'];
      $password = $_POST['password'];
      $email = $_POST['email'];

      $stmt = $pdo->prepare("UPDATE basic_crud SET username='$username', password='$password', email='$email' WHERE id=$id");
      $stmt->execute();
      echo "<script>alert('Updated Successfuly!'); window.location.href='index.php';</script>";
    }
    ?>
    <?php
    $stmt = $pdo->prepare("SELECT * FROM basic_crud WHERE id=$id");
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    ?>
    <div class="container mt-5">
      <div class="card">
        <div class="card-header">
          <h1 class="d-inline">Update Page</h1>
          <a href="index.php" class="btn btn-warning float-end">Back</a>
        </div>
        <div class="card-body">
          <form action="" method="post">
            <label for="">Username</label>
            <input type="text" name="username" class="form-control" placeholder="Enter Username" required value="<?php echo $data['username']; ?>">
            <label for="">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Enter Password" required value="<?php echo $data['password']; ?>">
            <label for="">Email</label>
            <input type="email" name="email" class="form-control" placeholder="Enter Email" required value="<?php echo $data['email']; ?>">
            <br>
            <button type="submit" class="btn btn-warning">Update</button>
          </form>
        </div>
      </div>
    </div>
  </body>
</html>
