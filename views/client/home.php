<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Home PHP</h1>

    <?php foreach ($data as $product) : ?>
        <h2><?= $product['name'] ?></h2>
        <p><?= $product['price'] ?></p>
    <?php endforeach; ?>
</body>
</html>