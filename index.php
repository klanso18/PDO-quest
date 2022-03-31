<?php
require_once 'connec.php';

$pdo = new PDO(DSN, USER, PASS);

if (isset($_POST['submit'])) {
    $friend = array_map("trim", $_POST);
    $friend = array_map("htmlentities", $friend);
    $firstname = $friend['firstname'];
    $lastname = $friend['lastname'];
    $errors = [];

    if (empty($friend['firstname'])) {
        $errors[] = "Firstname is mandatory";
    }
    if (empty($friend['lastname'])) {
        $errors[] = "Lastname is mandatory";
    }
    if (mb_strlen($friend['firstname']) > 45) {
        $errors[] = "Firstname length must be under 45 characters";
    }
    if (mb_strlen($friend['lastname']) > 45) {
        $errors[] = "Lastname length must be under 45 characters";
    }

    if (empty($errors)) {
        $query = 'INSERT INTO friend (firstname, lastname) VALUES (:firstname, :lastname)';
        $statement = $pdo->prepare($query);
        $statement->bindValue(':firstname', $friend['firstname'], PDO::PARAM_STR);
        $statement->bindValue(':lastname', $friend['lastname'], PDO::PARAM_STR);
        $statement->execute();
        header("Location: /");
    }
}

if (isset($_POST['delete'])) {
    $query = "DELETE FROM friend WHERE id > 4";
    $statement = $pdo->prepare($query);
    $res = $statement->execute();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<?php

$query = "SELECT firstname, lastname FROM friend";
$statement = $pdo->query($query);
$friends = $statement->fetchAll();

?>

<body>
    <h1>List of friends</h1>
    <ul>

        <?php foreach ($friends as $friend) : ?>
            <li>
                <?= $friend['firstname'] . ' ' . $friend['lastname'] . "<br>"; ?>
            </li>
        <?php endforeach ?>
    </ul>


    <form action="" method="post">
        <div>
            <label for="firstname">Firstname</label>
            <input type="text" name="firstname" id="firstname">
        </div>
        <div>
            <label for="lastname">Lastname</label>
            <input type="text" name="lastname" id="lastname">
        </div>
        <button type="submit" name="submit">Add a friend</button>
        <button type="delete" name="delete">Delete the new friends</button>

        <?php if (!empty($errors)) : ?>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?=$error?></li>
                    <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </form>
</body>

</html>