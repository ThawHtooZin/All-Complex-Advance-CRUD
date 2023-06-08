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
    $stmt = $pdo->prepare("SELECT * FROM basic_crud");
    $stmt->execute();
    $datas = $stmt->fetchall();
    ?>
    <div class="container mt-5">
      <div class="card">
        <div class="card-header">
          <h1 class="d-inline">Basic CRUD</h1>
          <a href="add.php" class="btn btn-success float-end">Add</a>
        </div>
        <div class="card-body">
          <table class="table">
            <thead>
              <tr>
                <th>#</th>
                <th>Username</th>
                <th>Password</th>
                <th>Email</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($datas as $data) {
                ?>
                <tr>
                  <td><?php echo $data['id']; ?></td>
                  <td><?php echo $data['username']; ?></td>
                  <td><?php echo $data['password']; ?></td>
                  <td><?php echo $data['email']; ?></td>
                  <td>
                    <a href="update.php?id=<?php echo $data['id']; ?>" class="btn btn-warning">Update</a>
                    <a href="delete.php?id=<?php echo $data['id']; ?>" class="btn btn-danger">Delete</a>
                  </td>
                </tr>
                <?php
              }
               ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </body>
</html>
