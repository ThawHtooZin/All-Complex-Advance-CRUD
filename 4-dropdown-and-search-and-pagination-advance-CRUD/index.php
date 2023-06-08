<?php include 'connect.php'; ?>
<?php

if(!empty($_POST['search'])){
  if($_POST['search']){
    setcookie('search', $_POST['search'], time() + (87400 * 36), "/");
  }
}
else{
  if(empty($_GET['pageno'])){
    unset($_COOKIE['search']);
    setcookie('search', null, -1, "/");
  }
}

?>
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
      $search = $_POST['search'];
      $stmt = $pdo->prepare("SELECT * FROM advance1_crud WHERE username LIKE '%$search%'");
      $stmt->execute();
      $datas = $stmt->fetchall();
    }else{
      $stmt = $pdo->prepare("SELECT * FROM advance1_crud");
      $stmt->execute();
      $datas = $stmt->fetchall();
    }
    ?>
    <div class="container mt-5">
      <div class="card">
        <div class="card-header">
          <h1 class="d-inline">Basic CRUD</h1>
          <a href="add.php" class="btn btn-success float-end">Add</a>
        </div>
        <?php
        if(!empty($_GET['pageno'])){
          $pageno = $_GET['pageno'];
        }else{
          $pageno = 1;
        }
        $numOfrecs = 3;
        $offset = ($pageno -1) * $numOfrecs;

        if(empty($_POST['search']) && empty($_COOKIE['search'])){
          $stmt = $pdo->prepare("SELECT * FROM advance1_crud ORDER BY id DESC");
          $stmt->execute();
          $rawResult = $stmt->fetchall();
          $total_pages = ceil(count($rawResult) / $numOfrecs);

          $stmt = $pdo->prepare("SELECT * FROM advance1_crud ORDER BY id DESC LIMIT $offset,$numOfrecs");
          $stmt->execute();
          $result = $stmt->fetchall();
        }else{
          if(!empty($_POST['search'])){
            $searchkey = $_POST['search'];
          }else{
            $searchkey = $_COOKIE['search'];
          }

          $stmt = $pdo->prepare("SELECT * FROM advance1_crud WHERE username LIKE '%$searchkey%' ORDER BY id DESC");
          $stmt->execute();
          $rawResult = $stmt->fetchall();
          $total_pages = ceil(count($rawResult) / $numOfrecs);

          $stmt = $pdo->prepare("SELECT * FROM advance1_crud WHERE username LIKE '%$searchkey%' ORDER BY id DESC LIMIT $offset,$numOfrecs");
          $stmt->execute();
          $result = $stmt->fetchAll();
        }
        ?>
        <div class="card-body">
          <form action="index.php" method="post">
            <button type="submit" class="btn btn-primary float-end">Search</button>
            <input type="text" name="search" class="form-control w-25 float-end" placeholder="Search">
          </form>
          <table class="table">
            <thead>
              <tr>
                <th>#</th>
                <th>Username</th>
                <th>Password</th>
                <th>Email</th>
                <th>Role</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if($result){
                $i = 1;
                foreach ($result as $data) {
                  ?>
                  <tr>
                    <td><?php echo $data['id']; ?></td>
                    <td><?php echo $data['username']; ?></td>
                    <td><?php echo $data['password']; ?></td>
                    <td><?php echo $data['email']; ?></td>
                    <td><?php echo $data['role']; ?></td>
                    <td>
                      <a href="update.php?id=<?php echo $data['id']; ?>" class="btn btn-warning">Update</a>
                      <a href="delete.php?id=<?php echo $data['id']; ?>" class="btn btn-danger">Delete</a>
                    </td>
                  </tr>
                  <?php
                  $i++;
                }
              }
                ?>
            </tbody>
          </table>
          <div aria-label="Page navigation example" style="float:right;">
            <ul class="pagination">
              <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
              <li class="page-item <?php if($pageno <= 1){echo 'disabled';} ?>">
                <a class="page-link" href="<?php if($pageno <= 1){echo '#';} else {echo "?pageno=".($pageno-1);} ?>">Previous</a>
              </li>
              <li class="page-item"><a class="page-link" href="#"><?php echo $pageno; ?></a></li>
              <li class="page-item <?php if($pageno >= $total_pages){echo 'disabled';}; ?>">
                <a class="page-link" href="<?php if($pageno >= $total_pages){echo '#';}else{echo "?pageno=".($pageno+1);} ?>">Next</a>
              </li>
              <li class="page-item"><a class="page-link" href="?pageno=<?php echo $total_pages; ?>">Last</a> </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
