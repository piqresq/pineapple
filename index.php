<?php

$error = '';
$mailRegex = "/^[\wp{-}\.0-9]+@{1}([\w-])+\.+[\w]{2,4}$/i";
$submitValid = false;

//validation

if (isset($_POST['submit'])) {
    $email = $_POST['mail'];
    if ($email == '') {
        $error = 'Field can\'t be empty';
    } else if (!preg_match($mailRegex, $email)) {
        $error = 'Enter correct mail';
    } else if (explode('.', $email)[count(explode('.', $email)) - 1] == 'co') {
        $error = 'We are not accepting subscriptions from Colombia emails';
    } else if (!isset($_POST['terms'])) {
        $error = 'You must accept to terms & conditions';
    } else {
        $error = '';
        $link = mysqli_connect("localhost", "root", "", "email_db");
        $sql = "INSERT INTO emails (email) VALUES ('$email')";
        mysqli_query($link, $sql);
        mysqli_close($link);
        $submitValid = true;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/pineapple.css">
    <title>pineapple</title>
</head>

<body>
    <main>
        <header>
            <a class="home" href="#">
                <img class="logo-pic" src="images/logo-img.svg" alt="Home">
                <img class="logo-text pine-logo-text" src="images/logo-text.svg" alt="pineapple" hidden>
            </a>
            <ul class="nav grey-text">
                <li class="about-link"><a href="#">About</a></li>
                <li class="how-link"><a href="#">How it works</a></li>
                <li class="contact-link"><a href="#">Contact</a></li>
            </ul>
        </header>
        <div class="content-container <?php if ($submitValid) echo "move-content" ?>" id="content">
            <div class="content">
                <img class="victory " id="ssimg" <?php if (!$submitValid) echo "hidden" ?> src="images/ic_success.svg" alt="Successsss">
                <h2 class="header <?php if ($submitValid) echo "sucs-header" ?>" id="header"><span>Subscribe to newsletter</span></h2>
                <p class="info grey-text <?php if ($submitValid) echo "sucs-info" ?>" id="info"><span>Subscribe to our newsletter and get 10% discount on <br hidden> pineapple
                        glasses.</span></p>
                <form method="POST" class=send-mail id="aform" action="" <?php if ($submitValid) echo "hidden" ?> onsubmit="return validateSubmit()">
                    <div class="input">
                        <input id="mail-field" name="mail" class="mail" type="text" oninput="return validateInput()" placeholder="Type your email address here…">
                        <button class="send enabled-arrow pine-arrow" name="submit" id="submit"></button>

                    </div>

                    <div id="err" class="spacer">
                        <noscript>
                            <?php
                            if ($error != '') {
                                echo "<div class=error>{$error}</div>";
                            }
                            ?>
                        </noscript>

                    </div>

                    <div class="terms-check">
                        <input type="checkbox" name="terms" id="terms-check" class="terms" onclick="return checkboxCheck(this)">
                        <label for="terms-check" class="terms-text grey-text">I agree to <a href="#">terms of
                                service</a></label>
                    </div>
                </form>
                <div class="separator"></div>
                <ul class="social-nav">
                    <li class="pine-facebook"><a href="#" class="facebook"><span class="path1"></span><span class="path2"></span><span class="path3"></span></a></li>
                    <li class="pine-instagram"><a href="#" class="instagram"><span class="path1"></span><span class="path2"></span><span class="path3"></span></a></li>
                    <li class="pine-twitter"><a href="#" class="twitter"><span class="path1"></span><span class="path2"></span><span class="path3"></span></a></li>
                    <li class="pine-youtube"><a href="#" class="youtube"><span class="path1"></span><span class="path2"></span><span class="path3"></span></a></li>
                </ul>
            </div>
        </div>
    </main>
    <div class="background"></div>
    <script src="JS/pineapple.js"></script>

</body>

</html>