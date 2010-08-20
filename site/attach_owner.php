<?php
    include_once("includes/header.inc");
    include_once("players.class.php");
    include_once("config.php");

    $actionattach = get_cgi_var("claimownership");
    $boatpseudo = get_cgi_var("boatpseudo");
    $password = get_cgi_var("password");

    function printAttachmentSummary($boatid = "", $boatpseudo = "") {
        $player = getLoggedPlayerObject();
        echo "<h2>".getLocalizedString("Attachment to this account")."</h2";
        echo "<ul>";
            echo "<li>".getLocalizedString("email")." : ".$player->email."</li>";
            echo "<li>".getLocalizedString("playername")." : ".$player->playername."</li>";
        echo "</ul>";
        echo "<h2>".getLocalizedString("Boat to attach")."</h2";
        echo "<ul>";
            echo "<li>".getLocalizedString("Boat id")." : ".$boatid."</li>";
            echo "<li>".getLocalizedString("Boat login")." : ".$boatpseudo."</li>";
        echo "</ul>";

    }

    function printFormRequest($boatpseudo = "", $password = "") {
        echo "<div id=\"attachboatbox\">";
        echo "<h2>".getLocalizedString("Here you can attach your boat to your player account. Please input your credentials.")."</h2>";
?>
        <form action="#" method="post" name="attachboat">
            <input size="25" maxlength="64" name="boatpseudo" value="<?php echo $boatpseudo; ?>" />
            <span class="texthelpers"><?php echo getLocalizedString("boatpseudo"); ?></span>
            <br />
            <input size="25" maxlength="15" name="password" value="<?php echo $password; ?>" />
            <span class="texthelpers"><?php echo getLocalizedString("password"); ?></span>
            <input type="hidden" name="claimownership" value="requested" />
            <br />
            <input type="submit" />
        </form> 
        <p><?php echo getLocalizedString("Log out to create a player account"); ?>.</p>
        </div>
<?php
    }

    if (!isPlayerLoggedIn()) { //il ne faut pas être loggué en tant que player, il faut être loggué en tant que boat
        echo "<div id=\"attachboatbox\">";
        echo "<p>";
        echo getLocalizedString("You have to be logklmged with the user/boat credential to attach the boat.");
        echo "</p></div>";
        include_once("includes/footer.inc");
        exit();
    }
        
    /* At this point :
     * - player credentials are checked
     * - we are logged in as a player
     * - the boat is not already attached to your account
     */
     
    if ($actionattach == "requested") { //REQUESTED
        if ($idu = checkAccount($boatpseudo, $password)) {
            $users = new users($idu);
    
            if ($users->getOwnerId() > 0) { //no way to reattach a boat
                $player = getPlayerObject($users->getOwnerId());
                echo "<div id=\"attachboatbox\">";
                echo "<p>";
                echo getLocalizedString("Current boat is already attached to the following player :")."&nbsp;";
                echo $player->htmlPlayername();
                echo "</p></div>";
                include_once("includes/footer.inc");
                exit();
            }
          
            echo "<h2>".getLocalizedString("Here is your request for attaching this boat")."&nbsp;:</h2>";
            echo "<div id=\"attachboatbox-request\">";
            printAttachmentSummary($users->idusers, $users->username);
?>
            <form action="#" method="post" name="attachboat">
                <input type="hidden" name="boatpseudo" value="<?php echo $boatpseudo; ?>"/>
                <input type="hidden" name="password" value="<?php echo $password; ?>"/>
                <input type="hidden" name="claimownership" value="confirmed"/>
                <input type="submit" value="<?php echo getLocalizedString("Confirm attachment request ?"); ?>" />
            </form> 
<?php
            echo "</div>";
        } else {
            echo "<h2>".getLocalizedString("Boat account is not valid.")."</h2>";
            printFormRequest($boatpseudo, $password);
        }
    } else if ($actionattach == "confirmed") { //CONFIRMED

        if ($idu = checkAccount($boatpseudo, $password)) {
            $users = getUserObject($idu);
            if ($users->setOwnerId(getPlayerId())) {
                echo "<div id=\"attachboatbox\">";
                echo '<h2>'.getLocalizedString("Attachment successful.").'</h2>';
                printAttachmentSummary($idu, $boatpseudo);
                echo "</div>";
            } else {
                echo "<h2>".getLocalizedString("It was not possible to attach this boat. Please report this error.")."</h2>";
                if ($users->error_status) {
                    print nl2br($users->error_string);
                }
                printFormRequest($boatpseudo, $password);
           }   
       }
    } else {
        printFormRequest($boatpseudo, $password);
    }
    include_once("includes/footer.inc");
  
?>