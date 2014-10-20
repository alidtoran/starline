<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">

    <head>    
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <title>Test</title>

        <link rel="stylesheet" type="text/css" href="<?php echo $this->baseUrl ?>/css/main.css" media="screen" />
    </head>

    <body class="b-document"> 
        <h1>Авторизация!</h1>
        
        <?php if($flag) { ?>
        <div class="b-msg b-msg_success">Здравствуйте, 
            <?php echo ($user->getAttribute('name') === '' ? 
                    $user->getAttribute('login') : $user->getAttribute('name'))?></div>
        <?php } else { ?>
        <form method="post" class="b-form">
            <div class="b-form__group<?php echo ($user->getError('login') !== NULL) ? ' b-form__group_error' : ''; ?>">
                <input type="text" name="login[login]" 
                       class="b-form__text"  value="<?php echo $user->getAttribute('login'); ?>" />
                <span class="b-form__helper"><?php echo $user->getError('login'); ?></span>
                <a href="<?php echo $this->baseUrl?>/registration" class="b-notice">Регистрация</a>
            </div>

            <div class="b-form__group<?php echo ($user->getError('password') !== NULL) ? ' b-form__group_error' : ''; ?>">
                <input type="password" name="login[password]" 
                       class="b-form__text" value="<?php echo $user->getAttribute('login'); ?>" />
                <span class="b-form__helper"><?php echo $user->getError('password'); ?></span>
                <a href="" class="b-notice">Забыл пароль</a>
            </div>

            <div class="b-form__group">
                <input type="submit" class="b-form__bttn" name="enter" value="Войти" />
            </div>
        </form>
        <?php } ?>
    </body>

</html>