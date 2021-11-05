
                <h2 class="visually-hidden">Публикации</h2>
                <?php foreach ($posts as $post):?>
                <article class="profile__post post post-<?=$post['class_name']?>"> 
                  <header class="post__header">
                    <h2><a href="post.php?post-id=<?=($post['id']); ?>"><?=$post['title']?></a></h2>
                  </header>

                <div class="post__main">
                    <?php if($post['class_name'] === 'photo'):?>
                      <!-- <h2><a href="post.php?post-id=<?=($post['id']); ?>"><?=$post['title']?></a></h2> -->
                      <div class="post-photo__image-wrapper">
                        <img src="<?=$post['img_path']?>" alt="Фото от пользователя" width="760" height="396">
                      </div>
                    <?php elseif($post['class_name'] === 'text'):?>
                      <!-- <h2><a href="post.php?post-id=<?=($post['id']); ?>"><?=$post['title']?></a></h2> -->
                      <p>
                        <?=$post['content']?>
                      </p>
                      <a class="post-text__more-link" href="#">Читать далее</a>
                    <?php elseif($post['class_name'] === 'video'):?>
                      <div class="post-video__preview">
                          <img src="img/coast.jpg" alt="Превью к видео" width="760" height="396">
                        </div>
                        <div class="post-video__control">
                          <button class="post-video__play post-video__play--paused button button--video" type="button"><span class="visually-hidden">Запустить видео</span></button>
                          <div class="post-video__scale-wrapper">
                            <div class="post-video__scale">
                              <div class="post-video__bar">
                                <div class="post-video__toggle"></div>
                              </div>
                            </div>
                          </div>
                          <button class="post-video__fullscreen post-video__fullscreen--inactive button button--video" type="button"><span class="visually-hidden">Полноэкранный режим</span></button>
                        </div>
                        <button class="post-video__play-big button" type="button">
                          <svg class="post-video__play-big-icon" width="27" height="28">
                            <use xlink:href="#icon-video-play-big"></use>
                          </svg>
                          <span class="visually-hidden">Запустить проигрыватель</span>
                        </button>
                      <?php elseif($post['class_name'] === 'quote'):?>
                        <blockquote>
                          <p>
                            <?=$post['content']?>
                          </p>
                          <cite cite><?=$post['author_quote']?></cite>
                        </blockquote>
                      <?php elseif($post['class_name'] === 'link'):?>
                        <div class="post-link__wrapper">
                          <a class="post-link__external" href="<?=$post['site_path']?>" title="Перейти по ссылке">
                            <div class="post-link__icon-wrapper">
                              <img src="https://www.google.com/s2/favicons?domain=<?= $post['site_path'] ?>" alt="Иконка">
                            </div>
                            <div class="post-link__info">
                              <h3><?=$post['title']?></h3>
                              <span><?=$post['site_path']?></span>
                            </div>
                            <svg class="post-link__arrow" width="11" height="16">
                              <use xlink:href="#icon-arrow-right-ad"></use>
                            </svg>
                          </a>
                        </div>
                      <?php endif ?>
                </div>

                  <footer class="post__footer">
                    <div class="post__indicators">
                      <div class="post__buttons">
                        <a class="post__indicator post__indicator--likes button" href="profile.php?id=<?=$user_profile['id']?>&post-id-likes=<?=($post['id'])?>" title="Лайк">
                          <svg class="post__indicator-icon" width="20" height="17">
                            <use xlink:href="#icon-heart"></use>
                          </svg>
                          <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                            <use xlink:href="#icon-heart-active"></use>
                          </svg>
                          <span><?=get_count_likes($connection, $post['id']); ?></span>
                          <span class="visually-hidden">количество лайков</span>
                        </a>
                        <a class="post__indicator post__indicator--repost button" href="#" title="Репост">
                          <svg class="post__indicator-icon" width="19" height="17">
                            <use xlink:href="#icon-repost"></use>
                          </svg>
                          <span>5</span>
                          <span class="visually-hidden">количество репостов</span>
                        </a>
                      </div>
                      <time class="post__time" datetime="<?=relativeDate($post['publictation_date'])?>"><?=relativeDate($post['publictation_date']) . " назад" ?></time>
                    </div>
                    <ul class="post__tags">
                      <?php $tags = get_tags_post($connection, $post['id']); foreach($tags as $tag): ?>
                      <li><a href="search.php?tag=<?=$tag['title']?>&id=<?=$tag['id']?>"><?="#" . $tag['title']?></a></li>
                      <?php endforeach ?>
                    </ul>
                  </footer>
                  <div class="comments">
                    <a class="comments__button button" href="profile.php?comments-post=<?=$post['id']?>">Показать комментарии</a>
                    <?php if(isset($post_id_comments)): ?>
                      <?="Список комментариев для постав {$post_id_comments}"?>
                    <?php endif ?>
                  </div>
                  </article> 
                <?php endforeach; ?> 
