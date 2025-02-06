<?php
if (isset($_POST["submitbtn"])) {
    include($_SERVER['DOCUMENT_ROOT'] . "/php crud/dbconnection.php");

    $name = mysqli_real_escape_string($con, $_POST['name']);
    $age = (int) $_POST['age'];
    $salary = (float) $_POST['salary'];
    $mono = mysqli_real_escape_string($con, $_POST['mono']);

    
    $image_folder = "uploads/images/";
    $resume_folder = "uploads/resumes/";

    if (!is_dir($image_folder)) {
        mkdir($image_folder, 0777, true); 
    }
    if (!is_dir($resume_folder)) {
        mkdir($resume_folder, 0777, true);
    }

    $image_name = $_FILES['ImageUpload']['name'];
    $image_size = $_FILES['ImageUpload']['size'];
    $temp_name = $_FILES['ImageUpload']['tmp_name'];
    $image_path = $image_folder . basename($image_name);

    $resume_name = $_FILES['resume']['name'];
    $resume_size = $_FILES['resume']['size'];
    $resume_temp = $_FILES['resume']['tmp_name'];
    $resume_path = $resume_folder . basename($resume_name);

    $fileType = strtolower(pathinfo($resume_path, PATHINFO_EXTENSION));
    $allowedTypes = array("pdf", "doc", "docx");

    if ($image_size > 1000000) {
        echo "Image size must be less than 1MB.";
        exit();
    }

    if (!in_array($fileType, $allowedTypes) || $resume_size > 2 * 1024 * 1024) {
        echo "Invalid resume format or size!";
        exit();
    }


    if (move_uploaded_file($temp_name, $image_path) && move_uploaded_file($resume_temp, $resume_path)) {
        
        $query = "INSERT INTO employee (name, age, salary, mono, image, file_name, file_path) VALUES ('$name','$age','$salary','$mono','$image_path','$resume_name,','$resume_path')";
        $stmt = mysqli_prepare($con, $query);
        

        if (mysqli_stmt_execute($stmt)) {
            header('location:viewData.php');
        } else {
            echo "Database Error: " . mysqli_error($con);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "File upload failed!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <div class="col-5 offset-4 mt-3 border shadow p-3">
        <h3 class="fs-3 text-center">Insert Record</h3>
        <form action="#" method="post" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Enter Name:" class="form-control mt-2 mb-2">

            <input type="text" name="age" placeholder="Enter Age:" class="form-control mb-2">

       
            <input type="text" name="salary" placeholder="Enter Salary:" class="form-control mb-2">

        
            <input type="text" name="mono" placeholder="Enter Mobile Number:" class="form-control mb-2">

            Upload Image:
            <input type="file" name="ImageUpload" required class="form-control mb-2">

            <input type="submit" value="insert" class="btn btn-info w-100" name="submitbtn">
        </form>
    </div>
</body>




</html>