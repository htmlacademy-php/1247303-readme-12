                <h2 class="visually-hidden">Подписки</h2>
                <ul class="profile__subscriptions-list">
                <?php foreach ($user_profile_subscriptions as $follower): ?>
                  <li class="post-mini post-mini--photo post user">
                    <div class="post-mini__user-info user__info">
                      <div class="post-mini__avatar user__avatar">
                        <a class="user__avatar-link" href="profile.php?id=<?=$follower['follower_user_id']?>&tabs=posts">
                          <img class="post-mini__picture user__picture" src="<?=$follower['avatar_path']?>" alt="Аватар пользователя">
                        </a>
                      </div>
                      <div class="post-mini__name-wrapper user__name-wrapper">
                        <a class="post-mini__name user__name" href="profile.php?id=<?=$follower['follower_user_id']?>&tabs=posts">
                          <span><?=$follower['first_name']. " " .$follower['last_name']?></span>
                        </a>
                        <time class="post-mini__time user__additional" datetime="<?=$follower['dt_add']?>"><?=relativeDate($follower['dt_add']) . " на сайте"?></time>
                      </div>
                    </div>
                    <div class="post-mini__rating user__rating">
                      <p class="post-mini__rating-item user__rating-item user__rating-item--publications">
                        <span class="post-mini__rating-amount user__rating-amount"><?=get_quantity_post($connection, $follower['follower_user_id'])?></span>
                        <span class="post-mini__rating-text user__rating-text">публикаций</span>
                      </p>
                      <p class="post-mini__rating-item user__rating-item user__rating-item--subscribers">
                        <span class="post-mini__rating-amount user__rating-amount"><?=get_quantity_followers($connection, $follower['follower_user_id'])?></span>
                        <span class="post-mini__rating-text user__rating-text">подписчиков</span>
                      </p>
                    </div>
                    <div class="post-mini__user-buttons user__buttons">
                      <?php if ($follower['follower_user_id'] != $user['id']):?>
                      <a href="profile.php?id=<?=$follower['follower_user_id']?>&subscriptions=1" class="post-mini__user-button user__button user__button--subscription button <?= (get_follower_id_from_user_id($connection, $user['id'], $follower['follower_user_id']) === $follower['follower_user_id']) ? "button--quartz" : "button--main" ?>" type="button"><?= (get_follower_id_from_user_id($connection, $user['id'], $follower['follower_user_id']) === $follower['follower_user_id']) ? "Отписаться" : "Подписаться" ?></a>
                      <?php endif;?>
                    </div>
                  </li>
                <?php endforeach; ?>
                </ul>
