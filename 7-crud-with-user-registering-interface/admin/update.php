<?php session_start(); ?>
<?php include 'config/connect.php'; ?>
<?php include 'config/config.php'; ?>
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
      $role = $_POST['role'];

      $stmt = $pdo->prepare("UPDATE advance2_crud SET username='$username', password='$password', email='$email', role='$role' WHERE id=$id");
      $stmt->execute();
      echo "<script>alert('Updated Successfuly!'); window.location.href='index.php';</script>";
    }
    ?>
    <?php
    $stmt = $pdo->prepare("SELECT * FROM advance2_crud WHERE id=$id");
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
            <input type="hidden" name="_token" value="<?php echo $_SESSION['_token']; ?>">
            <label for="">Username</label>
            <input type="text" name="username" class="form-control" placeholder="Enter Username" required value="<?php echo $data['username']; ?>">
            <label for="">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Enter Password" required value="<?php echo $data['password']; ?>">
            <label for="">Email</label>
            <input type="email" name="email" class="form-control" placeholder="Enter Email" required value="<?php echo $data['email']; ?>">
            <label for="">Role</label>
            <select class="form-control" name="role">
              <option value="admin" <?php if($data['role'] == "admin"){echo "selected";} ?>>admin</option>
              <option value="user" <?php if($data['role'] == "user"){echo "selected";} ?>>user</option>
            </select>
            <br>
            <button type="submit" class="btn btn-warning">Update</button>
          </form>
        </div>
      </div>
    </div>
  </body>
</html>
