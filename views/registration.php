<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">

    <head>    
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <title>Test</title>

        <link rel="stylesheet" type="text/css" href="<?php echo $this->baseUrl ?>/css/main.css" media="screen" />
    </head>

    <body class="b-document"> 
        <h1>Регистрация!</h1>
        
        <?php if($flag) { ?>
        <div class="b-msg b-msg_success">Регистрация прошла успешно!
            Теперь Вы можете <a href="<?php echo $this->baseUrl?>/login">авторизоваться</a></div>
        <?php } else { ?>
        <form method="post">
            <div id="login-group" class="b-form__group<?php echo ($user->getError('login') !== NULL) ? ' b-form__group_error' : ''; ?>">
                Логин: <input type="text" name="register[login]" data-field="login"
                              class="b-form__text"  value="<?php echo $user->getAttribute('login'); ?>" />
                <span id="login-error" class="b-form__helper"><?php echo $user->getError('login'); ?></span>
            </div>

            <div id="password-group" class="b-form__group<?php echo ($user->getError('password') !== NULL) ? ' b-form__group_error' : ''; ?>">
                Пароль: <input type="password" name="register[password]" data-field="password"
                               class="b-form__text"  value="<?php echo $user->getAttribute('password'); ?>" />
                <span id="password-error" class="b-form__helper"><?php echo $user->getError('password'); ?></span>
            </div>

            <div id="email-group" class="b-form__group<?php echo ($user->getError('email') !== NULL) ? ' b-form__group_error' : ''; ?>">
                E-mail: <input type="text" name="register[email]" data-field="email"
                               class="b-form__text"  value="<?php echo $user->getAttribute('email'); ?>" />
                <span id="email-error" class="b-form__helper"><?php echo $user->getError('email'); ?></span>
            </div>

            <div id="phone-group" class="b-form__group<?php echo ($user->getError('phone') !== NULL) ? ' b-form__group_error' : ''; ?>">
                Телефон: <input type="text" name="register[phone]" data-field="phone"
                                class="b-form__text"  value="<?php echo $user->getAttribute('phone'); ?>" />
                <span id="phone-error" class="b-form__helper"><?php echo $user->getError('phone'); ?></span>
            </div>

            <div id="name-group" class="b-form__group<?php echo ($user->getError('name') !== NULL) ? ' b-form__group_error' : ''; ?>">
                Имя: <input type="text" name="register[name]" data-field="name"
                            class="b-form__text"  value="<?php echo $user->getAttribute('name'); ?>" />
                <span id="name-error" class="b-form__helper"><?php echo $user->getError('name'); ?></span>
            </div>

            <div class="b-form__group<?php echo ($user->getError('sex') !== NULL) ? ' b-form__group_error' : ''; ?>">
                Пол: <label><input type="radio" name="register[sex]" value="0" checked="checked" /> Мужской</label>
                <label><input type="radio" name="register[sex]" value="1" /> Женский</label>
                <span class="b-form__helper"><?php echo $user->getError('sex'); ?></span>
            </div>

            <div id="date_birth-group" class="b-form__group<?php echo ($user->getError('date_birth') !== NULL) ? ' b-form__group_error' : ''; ?>">
                Дата рождения: <input type="text" name="register[date_birth]" data-field="date_birth"
                                      class="b-form__text"  value="<?php echo $user->getAttribute('date_birth'); ?>" />
                <span id="date_birth-error" class="b-form__helper"><?php echo $user->getError('date_birth'); ?></span>
            </div>

            <div class="b-form__group">
                <input type="submit" name="reg" class="b-form__bttn" value="Зарегистрироваться" />
            </div>
        </form>
        <?php } ?>
    </body>
    
    <script type="text/javascript">
        var form = {
            fields: {},
            rules: {},
            
            init: function()
            {
                var inputs = document.getElementsByTagName('input');
                
                for(var i in inputs){
                    if(typeof(inputs[i]) === 'undefined') continue;
                    if(typeof(inputs[i].getAttribute) === 'undefined') continue;
                    if(inputs[i].getAttribute('data-field') === null) continue;
                    
                    this.fields[inputs[i].getAttribute('data-field')] = inputs[i];
                    
                    inputs[i].onkeyup = function(){form.validateFields(); return true;};
                    inputs[i].onchange = function(){form.validateFields(); return true;};
                    inputs[i].onmouseup = function(){form.validateFields(); return true;};
                }
                
                return true;
            },
            
            validateFields: function()
            {
                for(var field in this.rules) {
                    if(typeof(this.fields[field]) === 'undefined') continue;
                    
                    document.getElementById(field + '-group').className = 'b-form__group';
                    document.getElementById(field + '-error').innerHTML = ''; 
                            
                    for(var rule in this.rules[field]) {
                        if(rule === 'required' && this.rules[field][rule] === true) {
                            if(this.fields[field].value.trim() === '') {
                                document.getElementById(field + '-group').className = 'b-form__group b-form__group_error';
                                document.getElementById(field + '-error').innerHTML = 'Поле должно быть заполнено';                                             
                            } 
                        } else if(rule === 'match') {
                           console.log(field + ': ' + this.rules[field][rule] + ' -> ' + this.fields[field].value + ' = ' + this.fields[field].value.match(this.rules[field][rule]));
                           if(this.fields[field].value.match(eval(this.rules[field][rule])) === null) {
                                document.getElementById(field + '-group').className = 'b-form__group b-form__group_error';
                                document.getElementById(field + '-error').innerHTML = 'Неверный формат';                                
                            } 
                        }
                    }
                }
                
                //alert('+7 (911) 111-22-33'.match(/\+7\s\([0-9]{3}\)\s[0-9]{3}\-[0-9]{2}\-[0-9]{2}/))
                
                return true;
            }
        };
        
        form.rules = <?php echo str_replace('^', '', str_replace('$', '', json_encode($user->rules)));?>;
        form.init();
    </script>
</html>