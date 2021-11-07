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



/**
 * Функция получает список постов из БД, и фильтрует список по запросу
 * @param mysqli $connection объект соединения с БД
 * @param ?int $type_id id категории типа контента
 * @param ?int $post_id id поста
 * @return array массив с списком постов
 */
function get_posts(mysqli $connection, ?int $type_id = NULL, ?int $post_id = NULL, ?int $user_id = NULL, int $offset = NULL): array
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
              type_id,
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
              posts.type_id = types.id 
             "; 


      if($type_id) {
        $sql .= "WHERE types.id = {$type_id} ";
      }
      if($post_id) {
        $sql .= "WHERE posts.id = {$post_id} ";
      }
      if($user_id) {
        $sql .= "WHERE users.id = {$user_id} ";
      }

      if($offset || $offset === 0) {
        $sql .= "LIMIT 5 OFFSET {$offset}";
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
function get_comments(mysqli $connection, ?int $post_id = NULL, ?int $limit = 2): array
{

    $sql = "SELECT
              comments.id,
              comments.publictation_date,
              comments.content,
              comments.publictation_date,
              comments.user_id,
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

            WHERE posts.id = {$post_id} ORDER BY `publictation_date` DESC";

    if ($limit) {
      $sql .= " LIMIT {$limit} OFFSET 0";
    };

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
  $sql = "SELECT id, avatar_path, first_name, last_name, dt_add FROM `users` WHERE id = '{$id}'";

  $user = get_array_db($connection, $sql);

  return $user[0];
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
/**
 * Функция возвращает список постов из БД, по поисковому текстовому запросу,
 * в случае отсутствия постов возвращает NULL
 * @param mysqli $connection объект соединения с БД
 * @param string $query запрос, по которому нужно осуществить поиск
 * @return array массив с списком постов
 */
function search_posts_db(mysqli $connection, string $query):?array 
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
    posts.count_view,
    site_path,
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
    posts.type_id = types.id 

  WHERE MATCH(title,content) AGAINST('{$query}')";


  $search_results = get_array_db($connection, $sql);

  
  if(count($search_results)) {

    return $search_results;
  }
  return null;
}
/**
 * Функция возвращает массив с ID постов из БД, по ID тега
 * в случае отсутствия постов возвращает NULL
 * @param mysqli $connection объект соединения с БД
 * @param int $tag_id - ID тега, по которому нужно получить ID постов
 * @return array массив с списком постов
 */
function get_posts_id_for_tags_id(mysqli $connection, int $tag_id):?array
{
  $sql = "SELECT post_id FROM `relations_posts_tags` WHERE tags_id = {$tag_id}";

  $post_id = get_array_db($connection, $sql);

  if(count($post_id)) {

    return $post_id;
  }
  return null;
}

/**
 * Функция возвращает массив постов из БД, по массиву ID нужных постов
 * @param mysqli $connection объект соединения с БД
 * @param array $posts_id - массив ID постов, по которому нужно получить массив с данными постов
 * @return array массив с списком постов
 */
function get_posts_for_id(mysqli $connection, array $posts_id):?array
{
    $results = [];

    foreach($posts_id as $post_id) {

        $result = get_posts($connection, NULL, $post_id["post_id"]);
      
        array_push($results, $result[0]);

    };

    if(count($results)) {

      return $results;
    }
    return null;
}


/**
 * Функция возвращает массив записей и таблицы `likes`, отфильтрованый по user_id
 * в случае отсутствия постов возвращает NULL
 * @param mysqli $connection объект соединения с БД
 * @param int $user_id - ID пользователя, по которому нужно получить ID постов
 * @return array массив с списком постов
 */
function get_posts_id_for_user_likes(mysqli $connection, int $user_id):?array
{
  $sql = "SELECT `id`, `post_id` FROM `likes` WHERE like_user_id = {$user_id}";

  $likes = get_array_db($connection, $sql);

  if(count($likes)) {

    return $likes;
  }
  return null;
}
/**
 * Функция возвращает ID записи из таблицы `likes`, данные фильтровуются по user_id и post_id
 * в случае отсутствия постов возвращает 0;
 * @param mysqli $connection объект соединения с БД
 * @param int $user_id - ID пользователя, по которому нужно получить ID постов
 * @param int $post_id - ID пользователя, по которому нужно получить ID постов
 * @return int - число, ID записи в из таблицы `likes`
 */

function get_likes_for_user_id_post_id(mysqli $connection, int $user_id, int $post_id):int
{
  
  $posts_likes = get_posts_id_for_user_likes($connection, $user_id);
  
  if(isset($posts_likes)){
    
    foreach ($posts_likes as $post) {

      if((int) $post['post_id'] === $post_id){

          return $post['id'];
      }
    }
  }

  return 0;
}

/**
* Создает или удаляет (тоглит) в таблице БД `likes` запись - связь поста и ID пользователя поставившего лайка.
* Принимает следующие параметры:
* @param  mysqli $connect обьект подключения к базе данных,
* @param  int $user_id, ID пользователя, необходимо связать с постом
* @param  int $post_id id поста, которого необходимо связать с пользователем в части лайка
* В случае успешной отправки возвращает true
*/
function toggle_likes_db(mysqli $connection, int $user_id, int $post_id)
{

  $like = get_likes_for_user_id_post_id($connection, $user_id, $post_id);

    if(!$like){
      
        $request = "
        INSERT INTO
            `likes` 
            (
              `like_user_id`, 
              `post_id`
            )

        VALUES
        (
        {$user_id},
        {$post_id}
        )"; 

        set_request_db($connection, $request);

        redirect_to_back();

        return;
    }
      
      delete_likes_db($connection, $like);

      redirect_to_back();
  
      return;
}

/**
* Удаляет в таблице БД `likes` запись - связь поста и ID пользователя поставившего лайка.
* Принимает следующие параметры:
* @param  mysqli $connect обьект подключения к базе данных,
* @param  int $id записи, которую нужно удалить
*/
function delete_likes_db(mysqli $connection, $id)
{
      $request = "DELETE FROM `likes` WHERE `id` = {$id}";

      return set_request_db($connection, $request);
    
}

/**
 * Функция получает информацию по постам пользователя у которых есть лайки
 * @param mysqli $connection объект соединения с БД
 * @param ?int $$user_id id пользователя, список постов 
 * @return array массив с списком постов
 */
function get_liked_posts(mysqli $connection, ?int $user_id = NULL): ?array
{

    $sql = "SELECT
              like_user_id,
              post_id,
              likes.dt_add,
              posts.user_id,
              posts.img_path,
              posts.video_path,
              users.first_name,
              users.last_name,
              users.avatar_path,
              posts.img_path,
              posts.type_id,
              types.class_name


            FROM `likes`
                          
            LEFT JOIN
              `posts`
            ON
              post_id = posts.id 

            LEFT JOIN
            `users`
            ON
              like_user_id = users.id

            LEFT JOIN
            `types`
            ON
              posts.type_id = types.id 
            
              
            WHERE posts.user_id = {$user_id}"; 


    return get_array_db($connection, $sql);
}

/**
 * Функция получает список подписчиков пользователя
 * @param mysqli $connection объект соединения с БД
 * @param ?int $$user_id id пользователя 
 * @return array массив с списком постов
 */
function get_subscritions(mysqli $connection, ?int $user_id = NULL): ?array
{

    $sql = "SELECT
              user_id,
              followerr_user_id,
              users.first_name,
              users.last_name,
              users.avatar_path,
              users.dt_add


            FROM `subscriptions`
                          
            LEFT JOIN
            `users`
            ON
             followerr_user_id = users.id
            
              
            WHERE user_id = {$user_id}"; 


    return get_array_db($connection, $sql);
}

/**
 * Функция получает список подписок пользователя
 * @param mysqli $connection объект соединения с БД
 * @param ?int $$user_id id пользователя 
 * @return array массив с списком постов
 */
function get_followers_id_from_user_id(mysqli $connection, int $user_id):?array
{
  $sql = "SELECT `id`, `user_id`, `followerr_user_id` FROM `subscriptions` WHERE followerr_user_id = {$user_id}";

  $followers = get_array_db($connection, $sql);

  return $followers;
}


/**
 * Функция получает ID подписчика пользователя
 * @param mysqli $connection объект соединения с БД
 * @param int $$user_id id пользователя 
 * @param int $follower_id подписчика
 * @return array массив с списком постов
 */
function get_follower_id_from_user_id(mysqli $connection, int $user_id, int $follower_id)
{
  
  $followers = get_followers_id_from_user_id($connection, $user_id);

  foreach($followers as $follower) {
  
    if((int) $follower['user_id'] === $follower_id){
      
      return $follower['user_id'];
    }
  };
  return 0;

}
/**
 * Функция получает ID пользователя по ID подписчика
 * @param mysqli $connection объект соединения с БД
 * @param int $$user_id id пользователя 
 * @param int $follower_id подписчика
 * @return array массив с списком постов
 */
function get_id_subcriptions_from_user_id(mysqli $connection, int $user_id, int $follower_id)
{
  
  $followers = get_followers_id_from_user_id($connection, $follower_id);

  foreach($followers as $follower) {
  
    if((int) $follower['user_id'] === $user_id){
      
      return $follower['id'];
    }
  };
  return 0;

}

/**
* Удаляет в таблице БД `likes` запись - связь поста и ID пользователя поставившего лайка.
* Принимает следующие параметры:
* @param  mysqli $connect обьект подключения к базе данных,
* @param  int $id записи, которую нужно удалить
*/
function delete_subscripions_db(mysqli $connection, $id)
{
      $request = "DELETE FROM `subscriptions` WHERE `id` = {$id}";

      return set_request_db($connection, $request);
    
}

/**
* Создает или удаляет (тоглит) в таблице БД `subscriptions` запись - связь ID пользователя и ID пользователя-подписчика.
* Принимает следующие параметры:
* @param  mysqli $connect обьект подключения к базе данных,
* @param  int $user_id, ID пользователя, на которого нужно подписаться
* @param  int $follower_id  id пользователя-подписчика
*/
function toggle_subscription_db(mysqli $connection, int $user_id, int $follower_id)
{

  $subscription = get_id_subcriptions_from_user_id($connection, $user_id, $follower_id);


    if(!$subscription){
      
        $request = "
        INSERT INTO
            `subscriptions` 
            (
              `user_id`, 
              `followerr_user_id`
            )

        VALUES
        (
        {$user_id},
        {$follower_id}
        )"; 

        set_request_db($connection, $request);

        redirect_to_back();

        return;
    }

    delete_subscripions_db($connection, $subscription);

    redirect_to_back();
  
    return;
}



/**
* Сохраняет в таблицу БД `comments` запись - комментарий к посту.
* Принимает следующие параметры:
* @param  mysqli $connect обьект подключения к базе данных,
* @param  string $comment_text текст комментария.
* @param  int $user_id ID автора комментария.
* @param  int $$post_id ID комментируемого поста.
* В случае успешной отправки возвращает true
*/
function add_comment_post_db(mysqli $connect, string $comment_text, int $user_id, int $post_id)
{
    $commented_post_is_valid = (int) get_posts($connect, null, $post_id, null, null)[0]['id']; 
    
    if($commented_post_is_valid === $post_id) {

      $today = new DateTime('now');

      $request = "
          INSERT INTO
          `comments`
          ( 
            `publictation_date`, 
            `content`, 
            `user_id`, 
            `post_id`
          )
          VALUES
          (
           '{$today->format('Y-m-d H:i:s')}',
           '{$comment_text}',
            {$user_id},
            {$post_id}
          )";
  
      return set_request_db($connect, $request);
    }
    print("Системная ошибка. Пост не найден");
    exit();
}

