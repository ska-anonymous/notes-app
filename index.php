<?php
// connect to the database
$conn = mysqli_connect("localhost", "root", "", "notes");
if (!$conn) {
    die("Failed to connect to Database: " . mysqli_connect_error());
}

$insert = false;
if (isset($_POST['btn_add_note'])) {
    $title = $_POST['note_title'];
    $description = $_POST['note_description'];

    $sql = "INSERT INTO `notes`(`title`,`description`) VALUES('$title', '$description')";
    $qry = mysqli_query($conn, $sql);
    if (!$qry) {
        $insert = false;
    } else {
        $insert = true;
    }
}


// update note if btn update is clicked
$update = false;
if (isset($_POST['btn_edit_note'])) {
    $note_id = $_POST['note_id'];
    $title = $_POST['edit_title'];
    $description = $_POST['edit_description'];

    $sql = "UPDATE `notes` SET `title` = '$title', `description` = '$description' WHERE `id` = '$note_id'";
    $qry = mysqli_query($conn, $sql);

    if ($qry) {
        $update = true;
    }
}


// delete note if note id is set by clicking delete button
$delete = false;
if (isset($_GET['delete'])) {
    $note_id = $_GET['delete'];
    $sql = "DELETE FROM `notes` WHERE `id`='$note_id'";
    $qry = mysqli_query($conn, $sql);
    if ($qry) {
        $delete = true;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <title>Notes App - Notes Taking made easy</title>
</head>

<body>

    <!-- Button trigger modal -->
    <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal">
        Edit Modal
    </button> -->

    <!-- Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editModalLabel">Edit Note</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="index.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="note_id" id="sno-edit">
                        <div class="form-group">
                            <label for="edit-title">Note Title</label>
                            <input type="text" name="edit_title" class="form-control" id="edit-title" required>
                        </div>
                        <div class="form-group my-3">
                            <label for="edit-description">Note Description</label>
                            <textarea name="edit_description" class="form-control" id="edit-description" cols="30" rows="5" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary my-3 btn-sm" name="btn_edit_note" type="submit">Update Note</button>
                        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                    </div>
                </form>
            </div>
        </div>
    </div>




    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Notes App</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact Us</a>
                    </li>
                </ul>
                <form class="d-flex" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="button">Search</button>
                </form>
            </div>
        </div>
    </nav>

    <?php

    if ($insert) {
        echo '
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success: </strong>Note Added Succesfully
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        ';
    }

    if ($update) {
        echo '
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success: </strong>Note Updated Succesfully
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        ';
    }

    if ($delete) {
        echo '
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success: </strong>Note Deleted Succesfully
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        ';
    }

    ?>





    <div class="container my-3">
        <h2>Add a Note</h2>
        <form action="index.php" method="POST">
            <div class="form-group">
                <label for="note-title">Note Title</label>
                <input type="text" name="note_title" class="form-control" id="note-title" required>
            </div>
            <div class="form-group my-3">
                <label for="note-description">Note Description</label>
                <textarea name="note_description" class="form-control" id="note-description" cols="30" rows="5" required></textarea>
            </div>
            <button class="btn btn-primary my-3" name="btn_add_note" type="submit">Add Note</button>
        </form>
    </div>


    <div class="container">
        <div class="row tools">
            <div class="search-box col-2 ml-auto">
                <span for="search">Search Notes</span>
                <input type="search" class="form-control" name="" id="search">
            </div>
        </div>
        <table class="table" id="notes-table">
            <thead>
                <tr>
                    <th>SNO</th>
                    <th>TITLE</th>
                    <th>DESCRIPTION</th>
                    <th>ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sno = 0;
                $sql = "SELECT * FROM `notes`";
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                    $sno++;
                ?>
                    <tr>
                        <th><?php echo $sno; ?></th>
                        <td><?php echo $row['title']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td>
                            <button class="btn-edit btn btn-sm btn-info" id="<?php echo $row['id'] ?>">Edit</button>
                            <button class="btn-delete btn btn-sm btn-danger" id="d<?php echo $row['id'] ?>">Delete</button>
                        </td>
                    </tr>

                <?php
                }
                ?>
            </tbody>
        </table>


    </div>



    <script src="bootstrap/js/jquery.slim.min.js"></script>
    <script src="bootstrap/js/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script>
        let editButtons = document.getElementsByClassName('btn-edit');
        Array.from(editButtons).forEach(button => {
            button.addEventListener("click", e => {
                let tr = e.target.parentElement.parentElement;
                let title = tr.getElementsByTagName("td")[0].innerText;
                let description = tr.getElementsByTagName("td")[1].innerText;

                document.getElementById('edit-title').value = title;
                document.getElementById('edit-description').value = description;
                document.getElementById('sno-edit').value = e.target.id;
                $("#editModal").modal("toggle");
            })
        });



        let deleteButtons = document.getElementsByClassName('btn-delete');
        Array.from(deleteButtons).forEach(button => {
            button.addEventListener('click', e => {
                let noteId = e.target.id.substr(1);
                if (confirm("Do you want to delete this note?")) {
                    window.location.replace("index.php?delete=" + noteId);
                }


            })
        })



        // Search input
        let searchInput = document.getElementById('search');
        searchInput.addEventListener('input', () => {
            let searchValue = searchInput.value;

            let trows = document.getElementById('notes-table').querySelector('tbody').querySelectorAll("tr");
            trows.forEach(tr => {
                tr.style.display = "none";
                let tds = tr.children;
                Array.from(tds).forEach(td=>{
                    if(td.textContent.toLowerCase().includes(searchValue.toLowerCase())){
                        tr.style.display = "table-row";
                    }
                })
            })
        })
    </script>
</body>

</html>