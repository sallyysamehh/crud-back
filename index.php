<?php
$host = "localhost";
$username = "root";
$password = "";
$dbName = "company";
$con = mysqli_connect($host, $username, $password, $dbName);

//create
if(isset($_POST["submit"])){
    $name = $_POST["name"];
    $phone = $_POST["phone"];
    $department = $_POST["department"];
    $gender = $_POST["gender"];

    //image code 
    $image_name = rand(0,255) . rand(0,255) . $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $location = "./upload/" . $image_name ;
    move_uploaded_file($image_tmp, $location);
    $insert = "INSERT INTO `employees` VALUES (NULL, '$name', '$phone', '$department', '$gender', '$image_name')";
    $insertQuery = mysqli_query($con, $insert);
}


//empty variables
    $mode = "create";
    $name = "";
    $phone = "";
    $department = "";
    $gender = "";
    $image = null;
    $userid = null;

//Edit query
if(isset($_GET['edit'])){
    $id = $_GET['edit'];
    $selectOne = "SELECT * FROM `employees` WHERE id = $id";
    $getOne = mysqli_query($con, $selectOne);
    $row = mysqli_fetch_assoc($getOne);
    $name = $row['name'];
    $phone = $row['phone'];
    $department = $row['department'];
    $gender = $row['gender'];
    $image = $row['image'];
    $userid = $id;
    $mode = "update";
}

if(isset($_POST["update"])){
    $name = $_POST["name"];
    $phone = $_POST["phone"];
    $department = $_POST["department"];
    $gender = $_POST["gender"];
    //image code 
    if($_FILES['image']['name'] == null){
        $image_name = $image;

    }else {
        $image_name = rand(0,255) . rand(0,255) . $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $location = "./upload/" . $image_name ;
        move_uploaded_file($image_tmp, $location);
        unlink("./upload/$oldImage");

    }
    

    $update = "UPDATE `employees` SET `name` = '$name', phone = '$phone', department = '$department', gender = '$gender', image = '$image_name' where id = $userid";
    $updateQuery = mysqli_query($con, $update);
    $mode = "create";
    header('Location: index.php');
    
}

//Delete query
if(isset($_GET['delete'])){
    $id = $_GET["delete"];
    //to get old image from database
    $selectOneDelete = "SELECT * FROM `employees` WHERE id = $id";
    $selectOneDeleteQuery = mysqli_query($con, $selectOneDelete);
    $rowDataDeleted = mysqli_fetch_assoc($selectOneDeleteQuery);
    $oldImage = $rowDataDeleted['image'];
    //to delete image from server
    unlink("./upload/$oldImage");
    //delete row
    $delete = "DELETE FROM `employees` WHERE id = $id";
    $deleteQuery = mysqli_query($con, $delete);
    header("Location: index.php");
}

//read query
$select = "SELECT * FROM `employees`";
$selectQuery = mysqli_query($con, $select);

//select theem
$selectTheem = "SELECT * FROM `theem` where id =1";
$Theem = mysqli_query($con, $selectTheem);
$rowTheem = mysqli_fetch_assoc($Theem);

if(isset($_GET['color'])){
    $color = $_GET['color'];
    $updateColor = "UPDATE theem SET color ='$color' where id =1";
    $updateColorQuery = mysqli_query($con, $updateColor );
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <?php if($rowTheem['color'] == 'dark') : ?>
        <link rel="stylesheet" href="./css/dark.css">
    <?php endif; ?>    
    
    <link rel="stylesheet" href="./css/main.css">
</head>
<body>
<?php if($rowTheem['color'] == 'dark') : ?>
    <a href="?color=light" class="btn btn-light">Light mood</a>
    <?php else : ?>
        <a href="?color=dark" class="btn btn-dark">Dark mood</a>
    <?php endif ; ?>    
<div class="container col-6 py-5">
<div class="row justify-content-center mt-5">
<h1 class="text-center">Create Employee</h1>
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="form-group mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" value="<?= $name ?>" class="form-control" name="name" id="name">
                    </div>
                    <div class="form-group mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" value="<?= $phone ?>" class="form-control" name="phone" id="phone">
                    </div>
                    <div class="form-group mb-3">
                        <label for="department" class="form-label">Department</label>
                        <input type="text" value="<?= $department ?>" class="form-control" name="department" id="department">
                    </div>
                    <div class="form-group mb-3">
                        <label for="gender" class="form-label">Gender</label>
                        <select name="gender" id="gender" class="form-select">
                           <?php if($gender == "male"):?>
                                <option selected value="male">Male</option>
                                <option value="female">Female</option>
                            <?php elseif($gender == "female"):?>
                                <option value="male">Male</option>
                                <option selected value="female">Female</option>
                            <?php else : ?>
                                <option value="male">Male</option>
                            <option value="female">Female</option>    
                            <?php endif;?>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Employee's Image : <?php if($image != null): ?> <img width="60" src="./upload/<?= $image ?> " <?php endif ;?> alt=""></label>
                        <input type="file" accept="image/*" name="image" class="form-control">
                    </div>
                    <div class="text-center form-group">
                        <?php if($mode == "create"):?>
                            <button name="submit" class="btn btn-primary">Add Employee</button>
                        <?php else :?>
                            <button name="update" class="btn btn-warning">update Employee</button>
                            <a href="index.php" class="btn btn-secondary">cancel</a>
                            <?php endif; ?>
                    </div>
            </form>
            </div>
        </div>
    </div>
    <div class="col-12 p-3">
        <table class="table table-dark">
            <tr>
                <th>id</th>
                <th>name</th>
                <th>phone</th>
                <th>department</th>
                <th>gender</th>
                <th>image</th>
                <th colspan="2">Action</th>
            </tr>
            <?php foreach ($selectQuery as $employee) :?>  
                <tr>
                    <td><?= $employee['id']?></td>
                    <td><?= $employee['name']?></td>
                    <td><?= $employee['phone']?></td>
                    <td><?= $employee['department']?></td>
                    <td><?= $employee['gender']?></td>
                    <td> <img width="80" src="./upload/<?= $employee['image']?>" alt=""></td>
                    <td><a href="?edit=<?= $employee['id']?>" class="btn btn-warning">Edit</a></td>
                    <td><a href="?delete=<?= $employee['id']?>" class="btn btn-danger">Delete</a></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>
</div>
    
</body>
</html>