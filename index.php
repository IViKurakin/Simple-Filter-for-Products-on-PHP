<?php
include 'config.php';

// Получаем параметры фильтрации из GET-запроса
$min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : 0;
$max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : 10000;
$color = isset($_GET['color']) ? $_GET['color'] : '';
$season = isset($_GET['season']) ? $_GET['season'] : '';
$type = isset($_GET['type']) ? $_GET['type'] : '';
$gender = isset($_GET['gender']) ? $_GET['gender'] : '';
$size = isset($_GET['size']) ? intval($_GET['size']) : 0;

// Строим SQL запрос с фильтрами
$sql = "SELECT * FROM shoes WHERE price BETWEEN $min_price AND $max_price";

if (!empty($color)) {
    $sql .= " AND color = '$color'";
}
if (!empty($season)) {
    $sql .= " AND season = '$season'";
}
if (!empty($type)) {
    $sql .= " AND type = '$type'";
}
if (!empty($gender)) {
    $sql .= " AND gender = '$gender'";
}
if ($size > 0) {
    $sql .= " AND size = $size";
}

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Фильтр обуви</title>
</head>
<body>
    <div class="container">
        <h1>Фильтр обуви</h1>
        
        <!-- Форма фильтров -->
        <form method="GET" class="filters">
            <div class="price-inputs">
                <div class="filter-group">
                    <label>Минимальная цена:</label>
                    <input type="number" name="min_price" value="<?php echo $min_price; ?>" min="0" max="10000">
                </div>
                <div class="filter-group">
                    <label>Максимальная цена:</label>
                    <input type="number" name="max_price" value="<?php echo $max_price; ?>" min="0" max="10000">
                </div>
            </div>
            
            <div class="filter-group">
                <label>Цвет:</label>
                <select name="color">
                    <option value="">Все цвета</option>
                    <option value="черный" <?php echo ($color == 'черный') ? 'selected' : ''; ?>>Черный</option>
                    <option value="белый" <?php echo ($color == 'белый') ? 'selected' : ''; ?>>Белый</option>
                    <option value="красный" <?php echo ($color == 'красный') ? 'selected' : ''; ?>>Красный</option>
                    <option value="синий" <?php echo ($color == 'синий') ? 'selected' : ''; ?>>Синий</option>
                    <option value="коричневый" <?php echo ($color == 'коричневый') ? 'selected' : ''; ?>>Коричневый</option>
                    <option value="бежевый" <?php echo ($color == 'бежевый') ? 'selected' : ''; ?>>Бежевый</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label>Сезон:</label>
                <select name="season">
                    <option value="">Все сезоны</option>
                    <option value="лето" <?php echo ($season == 'лето') ? 'selected' : ''; ?>>Лето</option>
                    <option value="зима" <?php echo ($season == 'зима') ? 'selected' : ''; ?>>Зима</option>
                    <option value="демисезон" <?php echo ($season == 'демисезон') ? 'selected' : ''; ?>>Демисезон</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label>Тип:</label>
                <select name="type">
                    <option value="">Все типы</option>
                    <option value="ботинки" <?php echo ($type == 'ботинки') ? 'selected' : ''; ?>>Ботинки</option>
                    <option value="туфли" <?php echo ($type == 'туфли') ? 'selected' : ''; ?>>Туфли</option>
                    <option value="сапоги" <?php echo ($type == 'сапоги') ? 'selected' : ''; ?>>Сапоги</option>
                    <option value="сандалии" <?php echo ($type == 'сандалии') ? 'selected' : ''; ?>>Сандалии</option>
                    <option value="кеды" <?php echo ($type == 'кеды') ? 'selected' : ''; ?>>Кеды</option>
                    <option value="кроссовки" <?php echo ($type == 'кроссовки') ? 'selected' : ''; ?>>Кроссовки</option>
                    <option value="полуботинки" <?php echo ($type == 'полуботинки') ? 'selected' : ''; ?>>Полуботинки</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label>Гендер:</label>
                <select name="gender">
                    <option value="">Все</option>
                    <option value="мужской" <?php echo ($gender == 'мужской') ? 'selected' : ''; ?>>Мужской</option>
                    <option value="женский" <?php echo ($gender == 'женский') ? 'selected' : ''; ?>>Женский</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label>Размер:</label>
                <select name="size">
                    <option value="0">Все размеры</option>
                    <?php for ($i = 38; $i <= 45; $i++): ?>
                        <option value="<?php echo $i; ?>" <?php echo ($size == $i) ? 'selected' : ''; ?>>
                            <?php echo $i; ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
            
            <button type="submit">Применить фильтры</button>
        </form>
        
        <!-- Результаты -->
        <div class="products">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <?php echo $row['image'] ? 'Изображение' : 'Нет изображения'; ?>
                        </div>
                        <div class="product-name"><?php echo htmlspecialchars($row['name']); ?></div>
                        <div class="product-price"><?php echo number_format($row['price'], 0, ',', ' '); ?> руб.</div>
                        <div class="product-details">
                            Цвет: <?php echo htmlspecialchars($row['color']); ?><br>
                            Сезон: <?php echo htmlspecialchars($row['season']); ?><br>
                            Тип: <?php echo htmlspecialchars($row['type']); ?><br>
                            Гендер: <?php echo htmlspecialchars($row['gender']); ?><br>
                            Размер: <?php echo $row['size']; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-results">
                    <h3>Товары не найдены</h3>
                    <p>Попробуйте изменить параметры фильтрации</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php
// Закрываем соединение
mysqli_close($conn);
?>