<?php include_once 'lib/get_movies.php'; ?>

<main>
    <h1>Фильмы</h1>

    <div class="block">
        <button type="button" class="block" id="add-movies-button">Добавить фильмы</button>

        <div class="hidden" id="add-movies">
            <div class="add-movies-block">
                <form method="post" action="../lib/add_movies.php" id="add-movie-form">
                    <label for="movie-title">Название</label>
                    <div class="block">
                        <input id="movie-title" name="title" type="text" size="50" placeholder="Введите название фильма">
                        <div class="error-block"></div>
                    </div>

                    <label for="movie-year">Год</label>
                    <div class="block">
                        <input id="movie-year" name="year" type="text" size="50" placeholder="Введите год выпуска фильма (с 1850 по 2020)">
                        <div class="error-block"></div>
                    </div>

                    <label for="movie-format">Формат</label>
                    <div class="block">
                        <select id="movie-format" name="format">
                            <option value="VHS">VHS</option>
                            <option value="DVD">DVD</option>
                            <option value="Blu-Ray">Blu-Ray</option>
                        </select>
                    </div>

                    <label for="movie-stars">Актёры</label>
                    <div class="block">
                        <input id="movie-stars" name="stars" type="text" size="50" placeholder="Введите актёров фильма через запятую">
                        <div class="error-block"></div>
                    </div>

                    <button class="block" id="add-movie-submit">Добавить</button>
                </form>
                <hr>
                <div class="block">
                    <form enctype="multipart/form-data" method="post" action="../lib/add_movies.php" id="from-file-form">
                        <label for="from-file">Загрузить из файла</label>
                        <div class="block">
                            <input id="from-file" type="file" name="file" accept="text/plain">
                            <button type="button" id="delete-file-button">Удалить файл</button>
                            <div class="error-block"></div>
                        </div>

                        <button class="block" id="from-file-submit">Загрузить</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="block">
        <?php if (!empty($movies)): ?>
            <div class="block">
                <form method="get">
                    <label for="by-title">Найти по названию фильма</label>
                    <div class="block">
                        <input id="by-title" type="text" name="title" size="50" placeholder="Введите название фильма">
                        <button>Найти</button>
                    </div>
                </form>
            </div>

            <div class="block">
                <form method="get">
                    <label for="by-actor">Найти по актёру</label>
                    <div class="block">
                        <input id="by-actor" type="text" name="star" size="50" placeholder="Введите имя актёра">
                        <button>Найти</button>
                    </div>
                </form>
            </div>

            <table>
                <tr>
                    <td><a href="<?= UrlHelper::buildUrl($title, $star, 'id', $page) ?>" title="Сортировать по идентификатору">ID</a></td>
                    <td><a href="<?= UrlHelper::buildUrl($title, $star, 'title', $page) ?>" title="Сортировать по названию">Название</a></td>
                    <td>Год</td>
                    <td>Формат</td>
                    <td>Актёры</td>
                    <td></td>
                </tr>
                <?php foreach ($movies as $movie): ?>
                    <form method="post" action="../lib/delete_movies.php">
                        <input type="hidden" name="id" value="<?= $movie['id'] ?>"/>
                        <tr>
                            <td><?= $movie['id'] ?></td>
                            <td><?= $movie['title'] ?></td>
                            <td><?= $movie['year'] ?></td>
                            <td><?= $movie['format'] ?></td>
                            <td>
                                <?= implode(', ', array_map(function ($star) {
                                    return $star['name'];
                                }, $movie['stars'])); ?>
                            </td>
                            <td>
                                <button id="delete-movie-button" onclick="return confirm('Вы уверены, что хотите удалить фильм?');">
                                    Удалить
                                </button>
                            </td>
                        </tr>
                    </form>
                <?php endforeach; ?>
            </table>

            <?php if ($pagesTotal > 1): ?>
                <br>
                <table>
                    <tr>
                        <td title="Первая страница">
                            <a href="<?= UrlHelper::buildUrl($title, $star, $sort) ?>">1</a>
                        </td>
                        <td>
                            <?php if ($page > 1): ?>
                                <a title="Предыдущая страница" href="<?= UrlHelper::buildUrl($title, $star, $sort, $page - 1) ?>">«</a>
                            <?php else: ?>
                                «
                            <?php endif; ?>
                        </td>
                        <td title="Текущая страница"><?= $page ?></td>
                        <td>
                            <?php if ($page < $pagesTotal): ?>
                                <a title="Следующая страница" href="<?= UrlHelper::buildUrl($title, $star, $sort, $page + 1) ?>">»</a>
                            <?php else: ?>
                                »
                            <?php endif; ?>
                        </td>
                        <td title="Последняя страница">
                            <a href="<?= UrlHelper::buildUrl($title, $star, $sort, $pagesTotal) ?>"><?= $pagesTotal ?></a>
                        </td>
                    </tr>
                </table>
            <?php endif; ?>
        <?php else: ?>
            Фильмов нет.<br>
        <?php endif; ?>
    </div>
</main>