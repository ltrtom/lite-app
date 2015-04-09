<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Route not Found</title>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
</head>
<body>
    <p>No route found for <strong><?= $pathInfo ?></strong></p>
    <table>
        <thead>
            <tr>
                <th>Method</th>
                <th>Route</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($routes as $route): ?>
                <tr>
                    <td><?= $route['method'] ?></td>
                    <td><?= $route['routes'] ?></td>
                </tr>

            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>
