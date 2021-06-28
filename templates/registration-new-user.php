<div class="container">
    <h1 class="page__title page__title--registration">Регистрация</h1>
  </div>
  <section class="registration container">
    <h2 class="visually-hidden">Форма регистрации</h2>
    <form class="registration__form form" action="#" method="post" enctype="multipart/form-data">
      <div class="form__text-inputs-wrapper">
        <div class="form__text-inputs">
          <div class="registration__input-wrapper form__input-wrapper">
            <label class="registration__label form__label" for="registration-email">Электронная почта <span class="form__input-required">*</span></label>
            <div class="form__input-section <?=(isset($form_errors["email"])) ? "form__input-section--error":""?>">
              <input class="registration__input form__input" id="registration-email" type="email" name="email" placeholder="Укажите эл.почту"  value="<?= ($filter_form_data["email"] && $form_errors) ? $filter_form_data["email"] : '' ?>">
              <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
              <div class="form__error-text">
                <h3 class="form__error-title">Данные некорректны</h3>
                <p class="form__error-desc"><?=$form_errors["email"]?></p>
              </div>
            </div>
          </div>
          <div class="registration__input-wrapper form__input-wrapper">
            <label class="registration__label form__label" for="registration-login">Логин <span class="form__input-required">*</span></label>
            <div class="form__input-section <?=(isset($form_errors["login"])) ? "form__input-section--error":""?>">
              <input class="registration__input form__input" id="registration-login" type="text" name="login" placeholder="Укажите логин" value="<?= ($filter_form_data["login"] && $form_errors) ? $filter_form_data["login"] : '' ?>">
              <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
              <div class="form__error-text">
                <h3 class="form__error-title">Данные некорректны</h3>
                <p class="form__error-desc"><?=$form_errors["login"]?></p>
              </div>
            </div>
          </div>
          <div class="registration__input-wrapper form__input-wrapper">
            <label class="registration__label form__label" for="registration-first_name">Имя<span class="form__input-required">*</span></label>
            <div class="form__input-section <?=(isset($form_errors["first_name"])) ? "form__input-section--error":""?>">
              <input class="registration__input form__input" id="registration-first_name" type="text" name="first_name" placeholder="Укажите имя" value="<?= ($filter_form_data["first_name"] && $form_errors) ? $filter_form_data["first_name"] : '' ?>">
              <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
              <div class="form__error-text">
                <h3 class="form__error-title">Данные некорректны</h3>
                <p class="form__error-desc"><?=$form_errors["first_name"]?></p>
              </div>
            </div>
          </div>
          <div class="registration__input-wrapper form__input-wrapper">
            <label class="registration__label form__label" for="registration-last_name">Фамилия <span class="form__input-required">*</span></label>
            <div class="form__input-section <?=(isset($form_errors["last_name"])) ? "form__input-section--error":""?>">
              <input class="registration__input form__input" id="registration-last_name" type="text" name="last_name" placeholder="Укажите фамилию" value="<?= ($filter_form_data["last_name"] && $form_errors) ? $filter_form_data["last_name"] : '' ?>">
              <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
              <div class="form__error-text">
                <h3 class="form__error-title">Данные некорректны</h3>
                <p class="form__error-desc"><?=$form_errors["last_name"]?></p>
              </div>
            </div>
          </div>
          <div class="registration__input-wrapper form__input-wrapper">
            <label class="registration__label form__label" for="registration-password">Пароль<span class="form__input-required">*</span></label>
            <div class="form__input-section <?=(isset($form_errors["password"])) ? "form__input-section--error":""?>">
              <input class="registration__input form__input" id="registration-password" type="password" name="password" placeholder="Придумайте пароль">
              <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
              <div class="form__error-text">
                <h3 class="form__error-title">Данные некорректны</h3>
                <p class="form__error-desc"><?=$form_errors["password"]?></p>
              </div>
            </div>
          </div>
          <div class="registration__input-wrapper form__input-wrapper">
            <label class="registration__label form__label" for="registration-password-repeat">Повтор пароля<span class="form__input-required">*</span></label>
            <div class="form__input-section <?=(isset($form_errors["password-repeat"])) ? "form__input-section--error":""?>">
              <input class="registration__input form__input" id="registration-password-repeat" type="password" name="password-repeat" placeholder="Повторите пароль">
              <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
              <div class="form__error-text">
                <h3 class="form__error-title">Данные некорректны</h3>
                <p class="form__error-desc"><?=$form_errors["password-repeat"]?></p>
              </div>
            </div>
          </div>
          <div class="registration__input-wrapper form__input-wrapper">
            <label class="registration__label form__label" for="avatar_path">Ваше фото</label>
            <div class="form__input-section <?=(isset($form_errors["file-photo"])) ? "form__input-section--error":""?>">
                <input class="registration__input form__input" id="avatar_path" type="file" name="file-photo" placeholder="Добавьте свою фотографию">
                <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                <div class="form__error-text">
                    <h3 class="form__error-title">Данные некорректны</h3>
                    <p class="form__error-desc"><?=$form_errors["file-photo"]?></p>
                </div>
            </div>   
          </div>
        </div>
        <?php if($form_errors): ?>
            <div class="form__invalid-block">
                <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
                <ul class="form__invalid-list">
                    <?php foreach($form_errors as $key => $value): ?>
                    <li class="form__invalid-item"><?=$value?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endif ?>
      </div>
      <button class="registration__submit button button--main" type="submit">Отправить</button>
    </form>
  </section>