<?php
include 'inc/header.php';
require 'inc/functions.php';

/*
In order to remember incomplete form data, we have to set the variables before the form post. Once filled in the value of the form fields are stored in these variables.
*/
$title = $date = $timeSpent = $whatILearned = $resourcesToRemember = '';

/*
Filter input and make sure that all fields are filled in. If all required fields are filled in users are redirected to the home page with the list of journal entries.
*/
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim(filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING));
    $date = date('Y-m-d', strtotime(str_replace('-', '/', $_POST['date'])));
    $timeSpent = trim(filter_input(INPUT_POST, 'timeSpent', FILTER_SANITIZE_STRING));
    $whatILearned = trim(filter_input(INPUT_POST, 'whatILearned', FILTER_SANITIZE_STRING));
    $resourcesToRemember = trim(filter_input(INPUT_POST, 'resourcesToRemember', FILTER_SANITIZE_STRING));

    if (empty($title) || empty($date) || empty($timeSpent) || empty($whatILearned) || empty($resourcesToRemember)) {
        $error_message = 'PLease fill in the required fields: Title, Date, Time Spent, What I Learned, Resources to Remember';
    } else {
        if (add_journal_entry($title, $date, $timeSpent, $whatILearned, $resourcesToRemember)) {
            header('location: index.php');
            exit;
        } else {
            $error_message = 'Could not add journal entry';
        }
    }
}
?>

<section>
    <div class="container">
        <div class="new-entry">
            <h2>New Entry</h2>
            <?php
            if (isset($error_message)) {
               echo "<p>$error_message</p>"; 
            }
            ?>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <label for="title">Title</label>
                <input id="title" type="text" name="title" value="<?php echo htmlspecialchars($title); ?>"><br>
                <label for="date">Date</label>
                <input id="date" type="date" name="date" value="<?php echo htmlspecialchars($date); ?>"><br>
                <label for="time-spent">Time Spent</label>
                <input id="time-spent" type="text" name="timeSpent" value="<?php echo htmlspecialchars($timeSpent); ?>"><br>
                <label for="what-i-learned">What I Learned</label>
                <textarea id="what-i-learned" rows="5" name="whatILearned"><?php echo htmlspecialchars($whatILearned); ?></textarea>
                <label for="resources-to-remember">Resources to Remember</label>
                <textarea id="resources-to-remember" rows="5" name="resourcesToRemember"><?php echo htmlspecialchars($resourcesToRemember); ?></textarea>
                <input type="submit" value="Publish Entry" class="button">
                <a href="#" class="button button-secondary">Cancel</a>
            </form>
        </div>
    </div>
</section>

<?php include("inc/footer.php"); ?>