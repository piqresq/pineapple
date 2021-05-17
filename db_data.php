<?php
session_start();
?>

<html>

<head>
    <title>Data</title>
    <style>
        tr,
        th,
        td {
            border: 1px solid black;
        }

        table,
        input,
        button {
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    <form method='POST' action='' name='filter-form'>
        <select name='filterby'>
            <option value='idasc'>ID ASC</option>
            <option value='iddesc'>ID DESC</option>
            <option value='emailasc'>EMAIL ASC </option>
            <option value='emaildesc'>EMAIL DESC</option>
        </select>
        <button name='filter'>Filter!</button>
        <br>
        <input type="text" name="search" placeholder='Enter email to find...'>
        <button name="submit-search">Search!</button>
        <input type='text' name='delete' placeholder='Enter email to delete...'>
        <button name='deletebtn'>Delete!</button>
    </form>

    <?php
    $querySearch = array_key_exists('qs', $_SESSION) ? $_SESSION['qs'] : null;
    $queryMail = array_key_exists('qm', $_SESSION) ? $_SESSION['qm'] : null;
    $queryFilter = array_key_exists('qf', $_SESSION) ? $_SESSION['qf'] : "`id` ASC";

    $link = mysqli_connect("localhost", "root", "", "email_db")  or die("Ошибка " . mysqli_error($link));

    $providers = "SELECT substring_index(email, '@', -1) domain FROM `emails` GROUP BY substring_index(email, '@', -1)";

    $query = "SELECT * FROM `emails`";

    $result = mysqli_query($link, $providers)  or die("Ошибка " . mysqli_error($link));

    $rows = mysqli_num_rows($result);

    $buttons = [];

    //create mail provider buttons

    echo "<form action='' name='buttons' method='POST'>
    <button name='reset'>all</button>";
    for ($i = 0; $i < $rows; $i++) {
        $row = mysqli_fetch_row($result);
        array_push($buttons, $row[0]);
        echo "
<button name='$row[0]'>$row[0]</button>";
    }
    echo "</form>";

    //mail delete

    if (isset($_POST['delete'])) {
        $mail = $_POST['delete'];
        $delquery = "DELETE FROM `emails` WHERE `email`='$mail'";
        $delresult = mysqli_query($link, $delquery);
    }

    //mail search

    if (isset($_POST['search']) && $_POST['search'] != '') {
        $querySearch = $_POST['search'];
        $query .= " WHERE `email` = '$querySearch'";
        $_SESSION['qs'] = $querySearch;
    } else if ($querySearch != null && isset($_POST['submit-search']))
        $_SESSION['qs'] = null;
    else if ($querySearch != null)
        $query .= " WHERE `email` = '$querySearch'";


    //filter by mail provider

    $newVal = false;

    for ($i = 0; $i < count($buttons); $i++) {
        if (isset($_POST['reset'])) {
            $newVal = true;
            $_SESSION['qm'] = null;
            break;
        } else if (isset($_POST[str_replace('.', '_', $buttons[$i])]) && $querySearch == null) {
            $queryMail = $buttons[$i];
            $query .= " WHERE `email` LIKE '%$queryMail'";
            $newVal = true;
            $_SESSION['qm'] = $queryMail;
            break;
        } else if (isset($_POST[str_replace('.', '_', $buttons[$i])])) {
            $queryMail  = $buttons[$i];
            $query .= " AND `email` LIKE '%$queryMail'";
            $newVal = true;
            $_SESSION['qm'] = $queryMail;
            break;
        }
    }
    if ($queryMail != null && $newVal == false && $querySearch == null)
        $query .= " WHERE `email` LIKE '%$queryMail'";
    else if ($queryMail != null && $newVal == false  && $querySearch != null)
        $query .= " AND `email` LIKE '%$queryMail'";

    //filter 

    if (isset($_POST['filter'])) {
        $val = $link->real_escape_string($_POST['filterby']);
        if ($val == 'idasc') {
            $queryFilter = "`id` ASC";
        } else if ($val == 'iddesc') {
            $queryFilter = "`id` DESC";
        } else if ($val == 'emailasc') {
            $queryFilter  = "`email` ASC";
        } else {
            $queryFilter = "`email` DESC";
        }
        $query .= " ORDER BY $queryFilter";
        $_SESSION['qf'] = $queryFilter;
    } else {
        $query .= " ORDER BY $queryFilter";
        $_SESSION['qf'] = $queryFilter;
    }

    //pagination

    if (isset($_GET['pageNum']))
        $pageNum = $_GET['pageNum'];
    else
        $pageNum = 1;
    $elements = 10;
    $curPage = ($pageNum - 1) * $elements;
    $pagesCount = "SELECT COUNT(*) FROM `emails`";
    $pagResult = mysqli_query($link, $pagesCount);
    $rows = mysqli_fetch_array($pagResult)[0];
    $pages = ceil($rows / $elements);
    $query .= " LIMIT $curPage, $elements";
    $data = mysqli_query($link, $query);

    //create table

    echo "

<table>
<tr>
    <th>ID</th>
    <th>Email</th>
</tr>";

    while ($row = mysqli_fetch_array($data)) {
        echo "
<tr>
    <td>{$row[0]}</td>
    <td>$row[1]</td>
</tr>
";
    }

    echo
    "</table>";

    //pagination buttons

    ?>



    <a href="?pageno=1">First</a></li>

    <a href="<?php if ($pageNum <= 1) {
                    echo '#';
                } else {
                    echo "?pageNum=" . ($pageNum - 1);
                } ?>">Previous</a>

    <a href="<?php if ($pageNum >= $pages) {
                    echo '#';
                } else {
                    echo "?pageNum=" . ($pageNum + 1);
                } ?>">Next</a>

</body>

</html>