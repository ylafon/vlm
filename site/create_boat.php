<?php
    include_once("includes/header.inc");
    include_once("players.class.php");
    include_once("config.php");

    requireLoggedPlayer();
    
    if (getLoggedPlayerObject()->hasMaxBoats() ) {
        printErrorAndDie("Restriction", "You already reached the maximum boats per player");
    }

    $actioncreate = get_cgi_var("createboat");

    function printFormRequest($boatpseudo = "", $boatname = "") {
        echo "<div id=\"createboatbox\">";
        echo "<h2>".getLocalizedString("Create your boat")."&nbsp;:</h2>";
?>
        <form action="#" method="post" accept-charset="utf-8" name="createboat">
            <input size="25" maxlength="32" name="boatpseudo" value="<?php echo $boatpseudo; ?>" />
            <span class="texthelpers"><?php echo getLocalizedString("boatpseudo"); ?></span>
            <br />
            <input size="25" maxlength="64" name="boatname" value="<?php echo $boatname; ?>" />
            <span class="texthelpers"><?php echo getLocalizedString("boatname"); ?></span>
            <input type="hidden" name="createboat" value="requested" />
            <br />
            <input type="submit" />
        </form> 
        <p><?php echo nl2br(getLocalizedString("The boatpseudo is unique and not changeable later.")); ?></p>

        </div>        <p><?php echo "<a href=\"attach_owner.php\">".getLocalizedString("You may also attach a pre-existing boat").".</a>"; ?></p>

<?php
    }

    $boatpseudo = htmlentities(get_cgi_var("boatpseudo"));
    $boatname = htmlentities(get_cgi_var("boatname"));

    if ($actioncreate == "requested") { //REQUESTED
        if (!checkLoginExists($boatpseudo)) {
            //FIXME : nom de boat correct ? 
            echo "<div id=\"createboatbox-request\">";
            echo "<h2>".getLocalizedString("Here is your request for creating a boat")."&nbsp;:</h2>";
            printBoatSummary($boatpseudo, $boatname);
?>
            <form action="#" method="post" accept-charset="utf-8" name="createboat">
                <input type="hidden" name="boatpseudo" value="<?php echo $boatpseudo; ?>"/>
                <input type="hidden" name="boatname" value="<?php echo $boatname; ?>"/>
                <input type="hidden" name="createboat" value="create"/>
                <input type="submit" value="<?php echo getLocalizedString("Confirm boat creation request ?"); ?>" />
            </form> 
<?php
            echo "</div>";
        } else {
            echo "<h2>".getLocalizedString("This boatpseudo already exists").".</h2>";
            printFormRequest($boatpseudo, $boatname);
        }
    } else if ($actioncreate == "create") { //CREATE
        $player = getLoggedPlayerObject();

        echo "<div id=\"createboatbox\">";
        if (!checkLoginExists($boatpseudo)
	    && (hash('sha256',$boatname) != $player->password) 
	    && $idu = createBoat($boatpseudo, $password = generatePassword($boatpseudo), $player->email, $boatname)) {
            //Manual creation of users, forcing use of MASTER server
            $users = new users($idu, FALSE);
            $users->initFromId($idu, True);
            echo "<h2>".getLocalizedString("Your boat has been created")."</h2>";
            printBoatSummary($boatpseudo, $boatname);
            echo "</div>";

            if ($users->setOwnerId($player->idplayers) && !$users->error_status) {
                echo "<div id=\"attachboatbox\">";
                echo '<h2>'.getLocalizedString("Attachment successful").'.</h2>';
                echo '<p>'.getLocalizedString('You own this boat').'.</p>';
                echo '<p><b>'.getLocalizedString('Click here').'</b>&nbsp;:&nbsp;'.$users->htmlIdusers().'</p>';
                echo "</div>";
            } else {
                echo "<h2>".getLocalizedString("It was not possible to attach this boat. Please report this error.")."</h2>";
                if ($users->error_status) {
                    print nl2br($users->error_string);
                }
            }
        } else {
            echo getLocalizedString("Boat creation error");
            echo "</div>";
        }
    } else {
        $player = getLoggedPlayerObject();
        echo "<p><a href=\"attach_owner.php\">".getLocalizedString("You may also attach a pre-existing boat").".</a></p>";
        echo $player->htmlBoatCandidatesList();
        printFormRequest();

    }
    
    include_once("includes/footer.inc");
?>
