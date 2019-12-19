<?php 
include 'inc/header.php';
require 'inc/functions.php';
?>

<section>
    <div class="container">
        <div class="entry-list">
            <?php
            foreach (get_journal_list() as $item) {
                echo '<article>' 
                . '<h2><a href="detail.php?id=' . $item["id"] . '">' . $item["title"] . '</a></h2>'
                . '<time datetime="2016-01-31">' . $item["date"] . '</time>'
                . '</article>';
            }
            ?>
        </div>
    </div>
</section>

<?php include("inc/footer.php"); ?>