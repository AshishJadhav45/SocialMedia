<?php
    session_start();
    require 'includes/dbh.inc.php';
    define('TITLE', "Polls | Orbit");

    if (!isset($_SESSION['userId'])) {
        header("Location: login.php");
        exit();
    }

    include 'includes/HTML-head.php';
?>

</head>

<body>

    <?php include 'includes/navbar.php'; ?>


    <div class="container">
        <div class="row">
            <div class="col-sm-3">

                <?php include 'includes/profile-card.php'; ?>

            </div>

            <div class="col-sm-9" id="user-section">

                <h1 class="my-5">Available Polls</h1>

                <?php
                $sql = "SELECT * FROM polls WHERE status = 1 ORDER BY created DESC";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<div class="card mb-3">';
                        echo '<div class="card-body">';
                        echo '<h5 class="card-title">' . $row['subject'] . '</h5>';
                        echo '<p class="card-text">' . $row['poll_desc'] . '</p>';
                        echo '<a href="poll.php?poll=' . $row['id'] . '" class="btn btn-primary">Vote</a>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="alert alert-info" role="alert">No polls available at the moment.</div>';
                }
                ?>

            </div>
        </div>
    </div>


    <?php include 'includes/footer.php'; ?>


    <?php include 'includes/HTML-footer.php'; ?>
