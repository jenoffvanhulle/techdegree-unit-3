<?php
include("inc/header.php");
include("inc/functions.php");

/*
Filter input
*/
if(isset($_GET["id"])) {
    $id = filter_input(INPUT_GET,"id",FILTER_SANITIZE_NUMBER_INT);
    $item = detail_array();
}

/*If $item is not set, if it's set to an empy string or if it's set to a false value, than it will redirect to the home page
*/
if (empty($item)) {
    header("location:/unit-3/index.php");
    exit;
}

/*
*/
if (isset($_POST['delete'])) {
    if (delete_entry(filter_input(INPUT_POST, 'delete', FILTER_SANITIZE_NUMBER_INT))) {
        header("Location: index.php?msg=Entry+Deleted");
        exit;
    } else {
        header('location: details.php?id=' . $id . '?msg=Unable+to+Delete+Entry');
        exit;
    }
}
if (isset($_GET['msg'])) {
    $error_message = trim(filter_input(INPUT_GET, 'msg', FILTER_SANITIZE_STRING));
}
?>

<section>
    <div class="container">
        <div class="entry-list single">
            <?php
            foreach (detail_array() as $item) {
                echo '<article>'
                . '<h1>' . $item["title"] . '</h1>'
                . '<time datetime="2016-01-31">' . $item["date"] . '</time>'
                . '<div class="entry">'
                . '<h3>Time Spent: </h3>'
                . '<p>' . $item["time_spent"] . '</p>'
                . '</div>'
                . '<div class="entry">'
                . '<h3>What I Learned:</h3>'
                . '<p>' . $item["learned"] . '</p>'
                . '</div>'
                . '<div class="entry">'
                . '<h3>Resources to Remember:</h3>'
                . '<p>' . $item["resources"] . '</p>'
                . '</div>'
                . '</article>';
            }
            ?>
        </div>
    </div>
    <div class="edit">
        <p><form><a href="edit.php?id=<?php echo $id ?>">Edit Entry</a></form></p>
        <form method='post' action='detail.php?id=<?php echo $id; ?>' onsubmit="return confirm('Are you sure you want to delete this project?');">
            <input type='hidden' value='<?php echo $id; ?>' name='delete' />
            <input type='submit' class='button--delete' value='Delete Entry' />
        </form>
    </div>
</section>

<?php include("inc/footer.php"); ?>