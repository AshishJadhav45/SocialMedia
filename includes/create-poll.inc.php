<?php
session_start();
require 'dbh.inc.php';

if (isset($_POST['poll-submit'])) {
    // Get form data
    $title = $_POST['title'];
    $isLocked = isset($_POST['is-locked']) ? 1 : 0;
    $description = $_POST['desc'];
    $options = $_POST['option'];

    // Validate form data
    if (empty($title) || empty($options)) {
        header("Location: ../create-poll.php?error=emptyfields");
        exit();
    } else {
        // Check if a poll with the same title already exists
        $sql = "SELECT * FROM polls WHERE subject=?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ../create-poll.php?error=sqlerror");
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "s", $title);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            $resultCheck = mysqli_stmt_num_rows($stmt);
            if ($resultCheck > 0) {
                header("Location: ../create-poll.php?error=titletaken");
                exit();
            } else {
                // Insert the poll into the database
                $sql = "INSERT INTO polls (subject, created, modified, status, created_by, poll_desc, locked) VALUES (?, NOW(), NOW(), 1, ?, ?, ?)";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    header("Location: ../create-poll.php?error=sqlerror");
                    exit();
                } else {
                    // Use a default value for created_by if userId is not available
                    $userId = isset($_SESSION['userId']) ? $_SESSION['userId'] : 0;
                    mysqli_stmt_bind_param($stmt, "sisi", $title, $userId, $description, $isLocked);
                    mysqli_stmt_execute($stmt);
                    $pollId = mysqli_insert_id($conn);

                    // Insert poll options into the database
                    $sql = "INSERT INTO poll_options (poll_id, name) VALUES (?, ?)";
                    $stmt = mysqli_stmt_init($conn);
                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        header("Location: ../create-poll.php?error=sqlerror");
                        exit();
                    } else {
                        foreach ($options as $option) {
                            mysqli_stmt_bind_param($stmt, "is", $pollId, $option);
                            mysqli_stmt_execute($stmt);
                        }
                        header("Location: ../create-poll.php?creation=success");
                        exit();
                    }
                }
            }
        }
    }
} else {
    header("Location: ../create-poll.php");
    exit();
}
