  <div class="container">
    <h1 class="page__title page__title--publication"><?=$post[0]["title"] ?></h1>
    <section class="post-details">
      <h2 class="visually-hidden">Публикация</h2>
      <div class="post-details__wrapper post-<?=$post[0]["class_name"]?>">
        <div class="post-details__main-block post post--details">
           <?= $post_content ?>
          <div class="post__indicators">
            <div class="post__buttons">
              <a class="post__indicator post__indicator--likes button" href="post.php?post-id=<?=($post[0]['id'])?>&post-id-likes=<?=($post[0]['id'])?>" title="Лайк">
                <svg class="post__indicator-icon" width="20" height="17">
                  <use xlink:href="#icon-heart"></use>
                </svg>
                <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                  <use xlink:href="#icon-heart-active"></use>
                </svg>
                <span><?=$quantity_likes?></span>
                <span class="visually-hidden">количество лайков</span>
              </a>
              <a class="post__indicator post__indicator--comments button" href="post.php?post-id=<?=$post[0]['id']?>&view-all-comments=1" title="Комментарии">
                <svg class="post__indicator-icon" width="19" height="17">
                  <use xlink:href="#icon-comment"></use>
                </svg>
                <span><?=$quantity_comments ?></span>
                <span class="visually-hidden">количество комментариев</span>
              </a>
              <?php if ((int) $post[0]['user_id'] != (int) $user['id']): ?>
              <a class="post__indicator post__indicator--repost button" href="post.php?post-id=<?=$post[0]['id']?>&repost=1" title="Репост">
                <svg class="post__indicator-icon" width="19" height="17">
                  <use xlink:href="#icon-repost"></use>
                </svg>
                <span><?=(get_count_repost($connection, $post[0]['id'])) ? get_count_repost($connection, $post[0]['id']) : "0"?></span>
                <span class="visually-hidden">количество репостов</span>
              </a>
              <?php endif; ?>
            </div>
            <span class="post__view"><?=$count_views." ".get_noun_plural_form($count_views, "просмотр", "просмотра", "просмотров")?></span>
          </div>
          <ul class="post__tags">
            <?php foreach ($tags as $tag): ?>
            <li><a href="search.php?tag=<?=$tag['title']?>&id=<?=$tag['id']?>"><?="#" . $tag['title']?></a></li>
            <?php endforeach ?>
          </ul>
          <div class="comments">
          <form class="comments__form form" action="post.php?post-id=<?=($post[0]['id'])?>" method="post">
            <div class="comments__my-avatar">
              <img class="comments__picture" src="<?=$user['avatar_path']?>" alt="Аватар пользователя">
            </div>
            <div class="form__input-section <?=(isset($form_errors["comment-text"])) ? "form__input-section--error" : ""?>">
              <textarea class="comments__textarea form__textarea form__input" id="comment-text" name="comment-text" placeholder="Ваш комментарий"><?= ($form_errors && $filter_form_data['comment-text']) ? $filter_form_data['comment-text'] : '' ?></textarea>
              <label class="visually-hidden">Ваш комментарий</label>
              <button class="form__error-button button" type="button">!</button>
                <div div class="form__error-text">
                  <h3 class="form__error-title">Ошибка валидации</h3>
                    <p class="form__error-desc"><?=isset($form_errors["comment-text"])?></p>
                </div>
            </div>
            <button class="comments__submit button button--green" type="submit">Отправить</button>
          </form>
            <div class="comments__list-wrapper">
              <ul class="comments__list">
                <?php foreach ($comments as $comment):?>
                <li class="comments__item user">
                    <div class="comments__avatar">
                        <a class="user__avatar-link" href="profile.php?id=<?=$comment['user_id']?>">
                            <img class="comments__picture" src="<?=$comment['avatar_path']?>" alt="Аватар пользователя">
                        </a>
                    </div>
                    <div class="comments__info">
                        <div class="comments__name-wrapper">
                            <a class="comments__user-name" href="profile.php?id=<?=$comment['user_id']?>">
                                <span><?=$comment['first_name']." ".$comment['last_name'] ?></span>
                            </a>
                            <time class="comments__time" datetime="<?=$comment['publication_date'] ?>"><?=relativeDate($comment['publication_date']) . " назад";?></time>
                        </div>
                        <p class="comments__text">
                          <?=$comment['content'] ?>
                        </p>
                    </div>
                </li>
                <?php endforeach ?>
              </ul>
              <?php if ($quantity_comments > 2 && !$view_all_comments): ?>
              <a class="comments__more-link" href="post.php?post-id=<?=$post[0]['id']?>&view-all-comments=1">
                <span>Показать все комментарии</span>
                <sup class="comments__amount"><?=$quantity_comments ?></sup>
              </a>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <div class="post-details__user user">
          <div class="post-details__user-info user__info">
            <div class="post-details__avatar user__avatar">
              <a class="post-details__avatar-link user__avatar-link" href="profile.php?id=<?=$post[0]['user_id']?>">
                <img class="post-details__picture user__picture" src="<?=$post[0]["avatar_path"]?>" alt="Аватар пользователя">
              </a>
            </div>
            <div class="post-details__name-wrapper user__name-wrapper">
              <a class="post-details__name user__name" href="profile.php?id=<?=$post[0]['user_id']?>">
                <span><?=$post[0]["first_name"]." ".$post[0]["last_name"] ?></span>
              </a>
              <?php $registration_date = $post[0]['dt_add']?>
              <time class="post-details__time user__time" datetime="<?=$registration_date?>"><?=relativeDate($registration_date) . " на сайте"?></time>
            </div>
          </div>
          <div class="post-details__rating user__rating">
            <p class="post-details__rating-item user__rating-item user__rating-item--subscribers">
              <span class="post-details__rating-amount user__rating-amount"><?=$followers_author?></span>
              <span class="post-details__rating-text user__rating-text"><?=get_noun_plural_form($followers_author, "подписчик", "подписчика", "подписчиков")?></span>
            </p>
            <p class="post-details__rating-item user__rating-item user__rating-item--publications">
              <span class="post-details__rating-amount user__rating-amount"><?=$quantity_post_author?></span>
              <span class="post-details__rating-text user__rating-text"><?=get_noun_plural_form($quantity_post_author, "публикация", "публикации", "публикаций")?></span>
            </p>
          </div>
          <div class="post-details__user-buttons user__buttons">
          <?php if ($user['id'] != $post[0]['user_id']):?>
          <a class="user__button user__button--subscription button <?= (get_id_from_followers_id_and_from_user_id($connection, $post[0]['user_id'], $user['id'])) ? "button--quartz" : "button--main" ?>" type="button" href="profile.php?id=<?=$post[0]['user_id']?>&subscriptions=1"><?= (get_id_from_followers_id_and_from_user_id($connection, $post[0]['user_id'], $user['id'])) ? "Отписаться" : "Подписаться" ?></a>
          <?php endif; ?>
        </div>
        </div>
      </div>
    </section>
  </div>