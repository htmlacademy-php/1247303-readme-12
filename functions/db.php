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
* Возвращает 1-ый строку массива данных из БД, на основании строки запроса. В случае ошибки, выводит на экран код ошибки.
* Принимает следующие параметры:
* @param  mysqli $connect обьект подключения к базе данных, 
* @param  string $request Строка запроса к базе данных.
*/
function get_count(mysqli $connect, string $request) 
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

  return get_count($connection,$sql);
};

/**
 * Функция получает из БД количество комментариев к посту
 * @param mysqli $connection объект соединения с БД
 * @param ?int $post_id число, id поста, по которому нужно получить количество комментариев
 */
function get_count_comments(mysqli $connection, int $post_id):int
{
  $sql = "SELECT COUNT(id) AS total_count FROM `comments` WHERE post_id = {$post_id}";

  return get_count($connection,$sql);
};

/**
 * Функция получает из БД количество публикаций (постов) пользователя
 * @param mysqli $connection объект соединения с БД
 * @param ?int $user_id число, id пользователя, по которому нужно получить количество публикаций
 */
function get_quantity_post(mysqli $connection, int $user_id):int
{
  $sql = "SELECT COUNT(id) AS total_count FROM `posts` WHERE user_id = {$user_id}";

  return get_count($connection,$sql);
};

/**
 * Функция получает из БД количество подписчиков пользователя
 * @param mysqli $connection объект соединения с БД
 * @param ?int $user_id число, id пользователя, по которому нужно получить количество подписчиков
 */
function get_quantity_followers(mysqli $connection, int $user_id):int
{
  $sql = "SELECT COUNT(id) AS total_count FROM `subscriptions` WHERE user_id = {$user_id}";

  return get_count($connection,$sql);
};

/**
 * Функция получает из БД количество просмотров публикации (поста)
 * @param mysqli $connection объект соединения с БД
 * @param ?int $post_idчисло, id публикации(поста), по которому нужно получить количество просмотров
 */
function get_count_views(mysqli $connection, int $post_id):int
{
  $sql = "SELECT SUM(count_view) FROM `posts` WHERE id = {$post_id}";

  return get_count($connection,$sql);
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
function get_tags(mysqli $connection, ?int $post_id = NULL): array
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

