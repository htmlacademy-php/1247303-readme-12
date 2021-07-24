<?php

/**
* Выполняет подключение к базе данных, возвращает объект подключения к серверу MySQL, в случае ошибки подключения, выводит на экран код ошибки.
* Принимает следующие параметры:
* @param  string $host имя хоста или IP-адрес,
* @param  string $user Имя пользователя MySQL,
* @param  string $password Пароль пользователя MySQL,
* @param  string $db Имя базы данных.
*/
function db_connect(string $host, string $user, string $password, string $db): mysqli
{
    $con = mysqli_connect($host, $user, $password, $db);

    if ($con == false) {
       print("Ошибка подключения: " . mysqli_connect_error());
       exit();
    }
    else {
        return $con;
    }
}


/**
* Сохраняет данные в БД, к которой уже произведено подключение на основании строки запроса. В случае ошибки, выводит на экран код ошибки.
* Принимает следующие параметры:
* @param  mysqli $connect обьект подключения к базе данных,
* @param  string $request Строка запроса к базе данных.
* В случае успешной отправки возвращает true
*/
function set_request_db(mysqli $connect, string $request)
{
  $query =  mysqli_query($connect, $request);

  if($query == false) {
    print("Ошибка запроса в БД: " .  mysqli_error($connect));
    exit();
  }
  else {

    return true;
  }
}

/**
* Возвращает массив с данными из базы данных, к которой уже произведено подключение на основании строки запроса. В случае ошибки, выводит на экран код ошибки.
* Принимает следующие параметры:
* @param  mysqli $connect обьект подключения к базе данных,
* @param  string $request Строка запроса к базе данных.
*/
function get_array_db(mysqli $connect, string $request): array
{
  $query =  mysqli_query($connect, $request);

  if($query == false) {
    print("Ошибка запроса в БД: " .  mysqli_error($connect));
    exit();
  }
  else {
    $array = mysqli_fetch_all($query, MYSQLI_ASSOC);

    return $array;
  }
}

/**
* Возвращает значение из массива данных из БД по нулевому индексу, на основании строки запроса. В случае ошибки, выводит на экран код ошибки.
* Принимает следующие параметры:
* @param  mysqli $connect обьект подключения к базе данных,
* @param  string $request Строка запроса к базе данных.
* TODO - Узнать у наставника, какой тип данных установить если функции может возвращать как данные, так и NULL
*/
function get_first_value(mysqli $connect, string $request)
{
  $query =  mysqli_query($connect, $request);

  if($query == false) {
    print("Ошибка запроса в БД: " .  mysqli_error($connect));
    exit();
  }
  else {
    $array = mysqli_fetch_array($query);

    $result = isset($array) ? $array[0] : 0;

  };
 
  return $result;
}

/**
 * Функция получает типы контента из БД
 * @param mysqli $connection объект соединения с БД
 * @return array массив с типами контента
 */
function get_content_types(mysqli $connection): array
{
    $sql = "SELECT type, id, class_name FROM types";
    return get_array_db($connection, $sql);
}


//TODO переписать несколько аргументов на один(массив)
/**
 * Функция получает список постов из БД, и фильтрует список по запросу
 * @param mysqli $connection объект соединения с БД
 * @param ?int $type_id id категории типа контента
 * @param ?int $post_id id поста
 * @return array массив с списком постов
 */
function get_posts(mysqli $connection, ?int $type_id = NULL, ?int $post_id = NULL): array
{

    $sql = "SELECT
              posts.id,
              posts.content,
              posts.title,
              posts.publictation_date,
              posts.user_id,
              posts.author_quote,
              posts.img_path,
              posts.video_path,
              site_path,
              users.dt_add,
              users.first_name,
              users.last_name,
              users.avatar_path,
              types.class_name

            FROM `posts`

            LEFT JOIN
              `users`
            ON
              posts.user_id = users.id

            LEFT JOIN
              `types`
            ON
              posts.type_id = types.id ";

      if($type_id) {
        $sql .= "WHERE types.id = {$type_id}";
      }
      if($post_id) {
        $sql .= "WHERE posts.id = {$post_id}";
      }

    return get_array_db($connection, $sql);
}

/**
 * Функция возвращает из БД количество лайков к посту
 * @param mysqli $connection объект соединения с БД
 * @param ?int $post_id число, id поста, по которому нужно подсчитать количество лайков
 */
function get_count_likes(mysqli $connection, int $post_id):int
{
  $sql = "SELECT COUNT(id) AS total_count FROM `likes` WHERE post_id = {$post_id}";

  return get_first_value($connection,$sql);
}

/**
 * Функция получает из БД количество комментариев к посту
 * @param mysqli $connection объект соединения с БД
 * @param ?int $post_id число, id поста, по которому нужно получить количество комментариев
 */
function get_count_comments(mysqli $connection, int $post_id):int
{
  $sql = "SELECT COUNT(id) AS total_count FROM `comments` WHERE post_id = {$post_id}";

  return get_first_value($connection,$sql);
}

/**
 * Функция получает из БД количество публикаций (постов) пользователя
 * @param mysqli $connection объект соединения с БД
 * @param ?int $user_id число, id пользователя, по которому нужно получить количество публикаций
 */
function get_quantity_post(mysqli $connection, int $user_id):int
{
  $sql = "SELECT COUNT(id) AS total_count FROM `posts` WHERE user_id = {$user_id}";

  return get_first_value($connection, $sql);
}

/**
 * Функция получает из БД количество подписчиков пользователя
 * @param mysqli $connection объект соединения с БД
 * @param ?int $user_id число, id пользователя, по которому нужно получить количество подписчиков
 */
function get_quantity_followers(mysqli $connection, int $user_id):int
{
  $sql = "SELECT COUNT(id) AS total_count FROM `subscriptions` WHERE user_id = {$user_id}";

  return get_first_value($connection,$sql);
}

/**
 * Функция получает из БД количество просмотров публикации (поста)
 * @param mysqli $connection объект соединения с БД
 * @param ?int $post_idчисло, id публикации(поста), по которому нужно получить количество просмотров
 */
function get_count_views(mysqli $connection, int $post_id):int
{
  $sql = "SELECT SUM(count_view) FROM `posts` WHERE id = {$post_id}";

  return get_first_value($connection,$sql);
}

/**
 * Функция получает список комментариев из БД к конкретной публикации
 * @param mysqli $connection объект соединения с БД
 * @param ?int $post_id id публикации (поста), по которой нужно получить список комментариев
 * @return array массив с списком комментариев
 */
function get_comments(mysqli $connection, ?int $post_id = NULL): array
{

    $sql = "SELECT
              comments.id,
              comments.publictation_date,
              comments.content,
              comments.publictation_date,
              users.first_name,
              users.last_name,
              users.avatar_path

            FROM `comments`

            LEFT JOIN
              `users`
            ON
              comments.user_id = users.id

            LEFT JOIN
              `posts`
            ON
              posts.id = comments.post_id

            WHERE posts.id = {$post_id}";

    return get_array_db($connection, $sql);
}

/**
 * Функция получает список тэгов из БД к конкретной публикации
 * @param mysqli $connection объект соединения с БД
 * @param ?int $post_id id публикации (поста), по которой нужно получить список тэгов
 * @return array массив с списком тэгов
 */
function get_tags_post(mysqli $connection, ?int $post_id = NULL): array
{

  $sql = "SELECT
    tags.id,
    tags.title

  FROM `tags`

  LEFT JOIN
    `relations_posts_tags`
  ON
  relations_posts_tags.tags_id = tags.id

  LEFT JOIN
    `posts`
  ON
    posts.id = relations_posts_tags.post_id

  WHERE posts.id = {$post_id}";

  return get_array_db($connection, $sql);
}

/**
 * Возвращает id тэга из БД по текстовому запросу. Если совпадение в БД не найдено возвращет 0
 * @param mysqli $connection объект соединения с БД
 * @param string $tag строка запроса (тега, id которого, нужно получить)
 * @return int id тэга или 0, если совпадения не найдены
 */
function get_tags_id(mysqli $connection, string $tag):int
{
  $sql = "SELECT id FROM `tags` WHERE title = '{$tag}'";

  return (int) get_first_value($connection, $sql);
}

/**
 * Проверяет есть ли пользователь в БД (табл. `users`) по текстовому запросу (email). Если совпадение в БД не найдено возвращет true, если нет - false
 * @param mysqli $connection объект соединения с БД
 * @param string $email строка запроса (e-mail, наличие которого нужно проверить в БД)
 * @return bool true, если пользователь с таким email есть, false если e-mail нет
 */
function get_user_by_mail(mysqli $connection, string $email):int
{
  $sql = "SELECT id FROM `users` WHERE email = '{$email}'";
  

  return get_first_value($connection, $sql);
}


/**
 * Возвращает из БД (табл. `users`) HASH пароля пользователя по текстовому запросу (email).
 * @param mysqli $connection объект соединения с БД
 * @param string $email строка запроса (e-mail, наличие которого нужно проверить в БД)
 * @return string
 */
function get_hash_by_mail(mysqli $connection, string $email):string
{
  $sql = "SELECT password FROM `users` WHERE email = '{$email}'";

  return get_first_value($connection, $sql);
}

/**
 * Возвращает из БД (табл. `users`) массив с именем и фамилией пользователя по ID).
 * @param mysqli $connection объект соединения с БД
 * @param string $id строка запроса (id, наличие которого нужно проверить в БД)
 * @return string
 */
function get_user(mysqli $connection, int $id):array
{
  $sql = "SELECT avatar_path, first_name, last_name FROM `users` WHERE id = '{$id}'";

  return get_array_db($connection, $sql);
}


/**
* Сохраняет в таблицу БД `posts` запись - публикацию (пост) с типом text(Текст).
* Принимает следующие параметры:
* @param  mysqli $connect обьект подключения к базе данных,
* @param  array $form_data массив данных из формы добавления поста.
* В случае успешной отправки возвращает true
*/
function add_post_text_db(mysqli $connect, ?array $form_data, int $user_id)
{

    $today = new DateTime('now');

    $request = "
        INSERT INTO
        `posts`(
          `user_id`,
          `type_id`,
          `publictation_date`,
          `title`,
          `content`,
          `count_view`
        )
        VALUES
        (
          {$user_id},
          {$form_data['submit']},
         '{$today->format('Y-m-d H:i:s')}',
         '{$form_data["text-heading"]}',
         '{$form_data["post-text"]}',
         1
        )";

    return set_request_db($connect, $request);
}


/**
* Сохраняет в таблицу БД `posts` запись - публикацию (пост) с типом quote(Цитата).
* Принимает следующие параметры:
* @param  mysqli $connect обьект подключения к базе данных,
* @param  array $form_data массив данных из формы добавления поста.
* В случае успешной отправки возвращает true
*/
function add_post_quote_db(mysqli $connect, ?array $form_data, int $user_id)
{

    $today = new DateTime('now');

    $request = "
        INSERT INTO
        `posts`(
          `user_id`,
          `type_id`,
          `publictation_date`,
          `title`,
          `content`,
          `author_quote`,
          `count_view`
        )
        VALUES
        (
          {$user_id},
          {$form_data['submit']},
         '{$today->format('Y-m-d H:i:s')}',
         '{$form_data["quote-heading"]}',
         '{$form_data["post-quote"]}',
         '{$form_data["quote-author"]}',
         1
        )";

    return set_request_db($connect, $request);
}

/**
* Сохраняет в таблицу БД `posts` запись - публикацию (пост) с типом photo(Фото).
* Принимает следующие параметры:
* @param  mysqli $connect обьект подключения к базе данных,
* @param  array $form_data массив данных из формы добавления поста.
* В случае успешной отправки возвращает true
*/
function add_post_photo_db(mysqli $connect, ?array $form_data, int $user_id)
{

    $today = new DateTime('now');

    $request = "
        INSERT INTO
        `posts`(
          `user_id`,
          `type_id`,
          `publictation_date`,
          `title`,
          `img_path`,
          `count_view`
        )
        VALUES
        (
          {$user_id},
          {$form_data['submit']},
         '{$today->format('Y-m-d H:i:s')}',
         '{$form_data["photo-heading"]}',
         '{$form_data["file-link"]}',
         1
        )";

    return set_request_db($connect, $request);
}

/**
* Сохраняет в таблицу БД `posts` запись - публикацию (пост) с типом video(Видео).
* Принимает следующие параметры:
* @param  mysqli $connect обьект подключения к базе данных,
* @param  array $form_data массив данных из формы добавления поста.
* В случае успешной отправки возвращает true
*/
function add_post_video_db(mysqli $connect, ?array $form_data, int $user_id)
{

    $today = new DateTime('now');

    $request = "
        INSERT INTO
        `posts`(
          `user_id`,
          `type_id`,
          `publictation_date`,
          `title`,
          `video_path`,
          `count_view`
        )
        VALUES
        (
          {$user_id},
          {$form_data['submit']},
         '{$today->format('Y-m-d H:i:s')}',
         '{$form_data["video-heading"]}',
         '{$form_data["video-url"]}',
         1
        )";

    return set_request_db($connect, $request);
}

/**
* Сохраняет в таблицу БД `posts` запись - публикацию (пост) с типом link(Ссылка).
* Принимает следующие параметры:
* @param  mysqli $connect обьект подключения к базе данных,
* @param  array $form_data массив данных из формы добавления поста.
* В случае успешной отправки возвращает true
*/
function add_post_link_db(mysqli $connect, ?array $form_data, int $user_id)
{

    $today = new DateTime('now');

    $request = "
        INSERT INTO
        `posts`(
          `user_id`,
          `type_id`,
          `publictation_date`,
          `title`,
          `site_path`,
          `count_view`
        )
        VALUES
        (
          {$user_id},
          {$form_data['submit']},
         '{$today->format('Y-m-d H:i:s')}',
         '{$form_data["link-heading"]}',
         '{$form_data["post-link"]}',
         1
        )";

    return set_request_db($connect, $request);
}

/**
* Сохраняет в таблицу БД `tags` запись - тег.
* Принимает следующие параметры:
* @param  mysqli $connect обьект подключения к базе данных,
* @param  string $tag_title наименование тега
* @param  int $tag_last_id id последней записи в таблице `tags` БД.
* В случае успешной отправки возвращает true
*/
function add_tag_db(mysqli $connect, string $tag_title)
{
    $request = "
        INSERT INTO
            `tags`(`title`)

        VALUES
        (
         '{$tag_title}'
        )";

        return set_request_db($connect, $request);

}

/**
* Сохраняет в таблицу БД `relations_posts_tags` запись - связь поста и тега.
* Принимает следующие параметры:
* @param  mysqli $connect обьект подключения к базе данных,
* @param  int $tag_id id тега, которого необходимо связать с постом
* @param  int $post_id id поста, которого необходимо связать с тегом
* В случае успешной отправки возвращает true
*/
function add_relations_db(mysqli $connect, int $tag_id, int $post_id)
{

    $request = "
        INSERT INTO
            `relations_posts_tags` 
            (
              `post_id`, 
              `tags_id`
            )

        VALUES
        (
         {$post_id},
         {$tag_id}
        )";

        return set_request_db($connect, $request);
}

/**
* Сохраняет в таблицу БД `tags` массив записей тегов.
* Принимает следующие параметры:
* @param  mysqli $connect обьект подключения к базе данных,
* @param  array $tags_arr массив наименований тегов
*/
function add_new_tags_db(mysqli $connection, array $tags_arr)
{

    foreach($tags_arr as $tag){

      if(get_tags_id($connection, $tag) === 0 && $tag){

        add_tag_db($connection, $tag);
      };
    };
}

/**
* Сохраняет в таблицу БД 'relations_posts_tags' массив записей, связей тегов и постов.
* Принимает следующие параметры:
* @param  mysqli $connection обьект подключения к базе данных,
* @param  int $post_id id поста, к которому нужно привязать теги
* @param  array $tags_arr массив наименований тегов, которые нужно привязать к посту
*/
function add_relation_arr_db(mysqli $connection, int $post_id, array $tags_arr)
{
    foreach($tags_arr as $tag) {

        $tag_id = get_tags_id($connection, $tag);

        add_relations_db($connection, $tag_id, $post_id);
    };
}



/**
* Сохраняет в таблицу БД `users` запись - учетную запись нового пользователя.
* Принимает следующие параметры:
* @param  mysqli $connect обьект подключения к базе данных,
* @param  array $form_data массив данных из формы регистрации пользователя.
* @return bool В случае успешной отправки возвращает true
*/
function add_user_db(mysqli $connect, ?array $form_data)
{

    $today = new DateTime('now');

    $request = "
        INSERT INTO
        `users`
        (
          `dt_add`,
          `email`,
          `login`,
          `password`,
          `avatar_path`,
          `first_name`,
          `last_name`
        )
        VALUES
        (
          '{$today->format('Y-m-d H:i:s')}',
          '{$form_data["email"]}',
          '{$form_data["login"]}',
          '{$form_data["password"]}',
          '{$form_data["file-link"]}',
          '{$form_data["first_name"]}',
          '{$form_data["last_name"]}'
        )";

    return set_request_db($connect, $request);
}




