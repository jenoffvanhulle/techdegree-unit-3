<?php 
include 'inc/header.php';
include 'inc/functions.php';

/*
Filter input
*/
$id = trim(filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT));

/*
An array called $item containing all the table data for a particular id
*/
$item = get_entry($id);

/*
Filter input and make sure that all fields are filled in. If all required fields are filled in users are redirected to the home page with the list of journal entries.
*/
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim(filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING));
    $date = date('Y-m-d', strtotime(str_replace('-', '/', $_POST['date'])));
    $timeSpent = trim(filter_input(INPUT_POST, 'timeSpent', FILTER_SANITIZE_STRING));
    $whatILearned = trim(filter_input(INPUT_POST, 'whatILearned', FILTER_SANITIZE_STRING));
    $resourcesToRemember = trim(filter_input(INPUT_POST, 'ResourcesToRemember', FILTER_SANITIZE_STRING));

    if (empty($title) || empty($date) || empty($timeSpent) || empty($whatILearned) || empty($resourcesToRemember)) {
        $error_message = 'PLease fill in the required fields: Title, Date, Time Spent, What I Learned, Resources to Remember';
    } else {
        if (edit_journal_entry($title, $date, $timeSpent, $whatILearned, $resourcesToRemember, $id)) {
            header('location: index.php');
            exit;
        } else {
            $error_message = 'Could not update this journal entry.';
        }
    }
}
?>

<section>
    <div class="container">
        <div class="edit-entry">
            <h2>Edit Entry</h2>
            <?php
            if (isset($error_message)) {
               echo "<p>$error_message</p>"; 
            }
            ?>
            <form method="post" action="edit.php?id=<?php echo $id ?>">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <label for="title"> Title</label>
                <input id="title" type="text" name="title" value="<?php echo htmlspecialchars($item['title']); ?>"><br>
                <label for="date">Date</label>
                <input id="date" type="date" name="date" value="<?php echo htmlspecialchars($item['date']); ?>"><br>
                <label for="time-spent"> Time Spent</label>
                <input id="time-spent" type="text" name="timeSpent" value="<?php echo htmlspecialchars($item['time_spent']); ?>"><br>
                <label for="what-i-learned">What I Learned</label>
                <textarea id="what-i-learned" rows="5" name="whatILearned"><?php echo htmlspecialchars($item['learned']); ?></textarea>
                <label for="resources-to-remember">Resources to Remember</label>
                <textarea id="resources-to-remember" rows="5" name="ResourcesToRemember"><?php echo htmlspecialchars($item['resources']); ?></textarea>
                <input type="submit" value="Update Entry" class="button">
                <a href="#" class="button button-secondary">Cancel</a>
            </form>
        </div>
    </div>
</section>

<?php include("inc/footer.php"); ?>