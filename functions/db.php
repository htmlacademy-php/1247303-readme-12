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
};


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
};

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
};

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
  
    return $array[0];
  } 
};

/**
 * Функция получает типы контента из БД
 * @param mysqli $connection объект соединения с БД
 * @return array массив с типами контента
 */
function get_content_types(mysqli $connection): array
{
    $sql = "SELECT type, id, class_name FROM types";
    return get_array_db($connection, $sql);
};


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
};

/**
 * Функция возвращает из БД количество лайков к посту
 * @param mysqli $connection объект соединения с БД
 * @param ?int $post_id число, id поста, по которому нужно подсчитать количество лайков
 */
function get_count_likes(mysqli $connection, int $post_id):int
{
  $sql = "SELECT COUNT(id) AS total_count FROM `likes` WHERE post_id = {$post_id}";

  return get_first_value($connection,$sql);
};

/**
 * Функция получает из БД количество комментариев к посту
 * @param mysqli $connection объект соединения с БД
 * @param ?int $post_id число, id поста, по которому нужно получить количество комментариев
 */
function get_count_comments(mysqli $connection, int $post_id):int
{
  $sql = "SELECT COUNT(id) AS total_count FROM `comments` WHERE post_id = {$post_id}";

  return get_first_value($connection,$sql);
};

/**
 * Функция получает из БД количество публикаций (постов) пользователя
 * @param mysqli $connection объект соединения с БД
 * @param ?int $user_id число, id пользователя, по которому нужно получить количество публикаций
 */
function get_quantity_post(mysqli $connection, int $user_id):int
{
  $sql = "SELECT COUNT(id) AS total_count FROM `posts` WHERE user_id = {$user_id}";

  return get_first_value($connection,$sql);
};

/**
 * Функция получает из БД количество подписчиков пользователя
 * @param mysqli $connection объект соединения с БД
 * @param ?int $user_id число, id пользователя, по которому нужно получить количество подписчиков
 */
function get_quantity_followers(mysqli $connection, int $user_id):int
{
  $sql = "SELECT COUNT(id) AS total_count FROM `subscriptions` WHERE user_id = {$user_id}";

  return get_first_value($connection,$sql);
};

/**
 * Функция получает из БД количество просмотров публикации (поста)
 * @param mysqli $connection объект соединения с БД
 * @param ?int $post_idчисло, id публикации(поста), по которому нужно получить количество просмотров
 */
function get_count_views(mysqli $connection, int $post_id):int
{
  $sql = "SELECT SUM(count_view) FROM `posts` WHERE id = {$post_id}";

  return get_first_value($connection,$sql);
};

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
};

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
};

/**
 * Возвращает id тэга из БД по текстовому запросу. Если совпадение в БД не найдено возвращет 0
 * @param mysqli $connection объект соединения с БД
 * @param string $tag строка запроса (тега, id которого, нужно получить)
 * @return int id тэга или 0, если совпадения не найдены
 */
$get_tags_id = function (mysqli $connection, string $tag):int
{  
  $sql = "SELECT id FROM `tags` WHERE title = '{$tag}'";

  return (int) get_first_value($connection, $sql);
};


/**
 * Функция получает id последней записи таблицы из БД 
 * @param mysqli $connection объект соединения с БД
 * @param string $name_table Имя таблицы, 
 * @return int id последнего записи
 */
$get_last_id = function(mysqli $connection, string $name_table):int
{

  $sql = "SELECT MAX(id) FROM `{$name_table}`";

  return get_first_value($connection,$sql);
};

/**
* Сохраняет в таблицу БД `posts` запись - публикацию (пост).
* Принимает следующие параметры:
* @param  mysqli $connect обьект подключения к базе данных, 
* @param  array $form_data массив данных из формы добавления поста.
* В случае успешной отправки возвращает true
*/
function add_post_db(mysqli $connect, array $form_data, string $type_content, int $post_id)
{
  
    $today = new DateTime('now');

    $request = "
        INSERT INTO 
            `posts` 

        VALUES 
        (
         {$post_id},
         2, 
         {$form_data['submit']}, 
         '{$today->format('Y-m-d H:i:s')}', 
         '{$form_data["{$type_content}-heading"]}',
         '{$form_data["post-{$type_content}"]}',
         '{$form_data["quote-author"]}', 
         '{$form_data["file-link"]}', 
         '{$form_data["video-url"]}', 
         '{$form_data["post-link"]}', 
         15 
        )";

    return set_request_db($connect, $request);

};

/**
* Сохраняет в таблицу БД `tags` запись - тег.
* Принимает следующие параметры:
* @param  mysqli $connect обьект подключения к базе данных, 
* @param  string $tag_title наименование тега
* @param  int $tag_last_id id последней записи в таблице `tags` БД.
* В случае успешной отправки возвращает true
*/
$add_tag_db = function(mysqli $connect, string $tag_title, int $tag_last_id)
{
    $tag_id = $tag_last_id + 1;

    $request = "
        INSERT INTO 
            `tags` 

        VALUES 
        (
         {$tag_id},
         '{$tag_title}'  
        )";

        return set_request_db($connect, $request);

};

/**
* Сохраняет в таблицу БД `relations_posts_tags` запись - связь поста и тега.
* Принимает следующие параметры:
* @param  mysqli $connect обьект подключения к базе данных, 
* @param  int $tag_id id тега, которого необходимо связать с постом
* @param  int $post_id id поста, которого необходимо связать с тегом
* @param  int $relation_last_id id последней записи в таблице `relations_posts_tags` БД.
* В случае успешной отправки возвращает true
*/
$add_relatios_db = function(mysqli $connect, int $tag_id, int $post_id, int $relation_last_id)
{
    $relation_id = $relation_last_id + 1;

    $request = "
        INSERT INTO 
            `relations_posts_tags` 

        VALUES 
        (
         {$relation_id},
         {$post_id},
         {$tag_id}  
        )";

        return set_request_db($connect, $request);
};

/**
* Сохраняет в таблицу БД `tags` массив записей тегов.
* Принимает следующие параметры:
* @param  mysqli $connect обьект подключения к базе данных, 
* @param  array $tags_arr массив наименований тегов
* @param  function $add_tag_db функция для добавления 1 записи в `tags`, используется как функция обратного вызова
* @param  function $get_last_id функция для получения id последней записи в `tags`, используется как функция обратного вызова
*/
function add_new_tags_db(mysqli $connection, array $tags_arr, $add_tag_db, $get_last_id, $get_tags_id) 
{

    foreach($tags_arr as $tag){

      if($get_tags_id($connection, $tag) === 0 && $tag){

        $last_tags_id = $get_last_id($connection, "tags");

        $add_tag_db($connection, $tag, $last_tags_id);
      };
    };
};

/**
* Сохраняет в таблицу БД 'relations_posts_tags' массив записей, связей тегов и постов.
* Принимает следующие параметры:
* @param  mysqli $connection обьект подключения к базе данных,
* @param  int $post_id id поста, к которому нужно привязать теги
* @param  array $tags_arr массив наименований тегов, которые нужно привязать к посту
* @param  function $get_tags_id функция для получения id в таблице `tags`, используется как функция обратного вызова
* @param  function $get_last_id функция для получения id последней записи в 'relations_posts_tags', используется как функция обратного вызова
* @param  function $add_relatios_db функция для внесения записи в 'relations_posts_tags', используется как функция обратного вызова
*/
function add_relation_arr_db(mysqli $connection, int $post_id, array $tags_arr, $get_tags_id, $get_last_id, $add_relatios_db) 
{
    foreach($tags_arr as $tag) {

        $tag_id = $get_tags_id($connection, $tag);

        $last_relation_id = $get_last_id($connection, 'relations_posts_tags') + 1;

        $add_relatios_db($connection, $tag_id, $post_id, $last_relation_id);
    };
};




