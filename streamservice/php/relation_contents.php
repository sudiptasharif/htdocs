<?php
    include_once 'db_oracle.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Nunito&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/global.css">
    <title>Relation Contents</title>
</head>
<body>
    <h2>Genre</h2>
    <?php printTable(TBL_GENRE, TBL_GENRE_COLS);?>
    <h2>Movie</h2>
    <?php printTable(TBL_MOVIE, TBL_MOVIE_COLS);?>
    <h2>Actor</h2>
    <?php printTable(TBL_ACTOR, TBL_ACTOR_COLS);?>
</body>
</html>