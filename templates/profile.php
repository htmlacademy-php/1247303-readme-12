      <h1 class="visually-hidden">Профиль</h1>
      <div class="profile profile--default">
        <div class="profile__user-wrapper">
          <div class="profile__user user container">
            <div class="profile__user-info user__info">
              <div class="profile__avatar user__avatar">
                <img class="profile__picture user__picture" src="<?=$user_profile['avatar_path']?>" alt="Аватар пользователя">
              </div>
              <div class="profile__name-wrapper user__name-wrapper">
                <span class="profile__name user__name"><?=$user_profile['first_name']?><br><?=$user_profile['last_name']?></span>
                <time class="profile__user-time user__time" datetime="<?=$user_profile['dt_add']?>"><?=relativeDate($user_profile['dt_add']) . " на сайте"?></time>
              </div>
            </div>
            <div class="profile__rating user__rating">
              <p class="profile__rating-item user__rating-item user__rating-item--publications">
                <span class="user__rating-amount"><?=$quantity_posts ?></span>
                <span class="profile__rating-text user__rating-text">публикаций</span>
              </p>
              <p class="profile__rating-item user__rating-item user__rating-item--subscribers">
                <span class="user__rating-amount"><?=$quantity_followers?></span>
                <span class="profile__rating-text user__rating-text">подписчиков</span>
              </p>
            </div>
            <div class="profile__user-buttons user__buttons">
              <?php if ($user_profile['id'] != $user['id']):?>
              <a class="profile__user-button user__button user__button--subscription button <?= (get_follower_id_from_user_id($connection, $user['id'], $user_profile['id']) === $user_profile['id']) ? "button--quartz" : "button--main" ?>" type="button" href="profile.php?id=<?=$user_profile['id']?>&subscriptions=1"><?= (get_follower_id_from_user_id($connection, $user['id'], $user_profile['id']) === $user_profile['id']) ? "Отписаться" : "Подписаться" ?></a>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <div class="profile__tabs-wrapper tabs">
          <div class="container">
            <div class="profile__tabs filters">
              <b class="profile__tabs-caption filters__caption">Показать:</b>
              <ul class="profile__tabs-list filters__list tabs__list">
                <li class="profile__tabs-item filters__item">
                  <a class="profile__tabs-link filters__button <?= ($tabs_active === 'posts') ? "filters__button--active" : "" ?> tabs__item button" href="profile.php?id=<?=$user_profile['id']?>&tabs=posts">Посты</a>
                </li>
                <li class="profile__tabs-item filters__item">
                  <a class="profile__tabs-link filters__button <?= ($tabs_active === 'likes') ? "filters__button--active" : "" ?> tabs__item button" href="profile.php?id=<?=$user_profile['id']?>&tabs=likes">Лайки</a>
                </li>
                <li class="profile__tabs-item filters__item">
                  <a class="profile__tabs-link filters__button <?= ($tabs_active === 'subscriptions') ? "filters__button--active" : "" ?> tabs__item button" href="profile.php?id=<?=$user_profile['id']?>&tabs=subscriptions">Подписки</a>
                </li>
              </ul>
            </div>
            <div class="profile__tab-content">
              <section class="profile__<?=$tabs_active?> tabs__content tabs__content--active">
                <?=$tab_content?>
              </section>
            </div>
          </div>
        </div>
      </div>