import { extend } from 'vee-validate';
import { required, email } from 'vee-validate/dist/rules';

extend('required', {
  ...required,
  message: 'Это поле обязательно для заполнения',
});

extend('email', {
  ...email,
  message: 'Неправильный email',
});

extend('username', (username) => {
  if (/^[a-zA-Z0-9]+$/.test(username.trim())) {
    return true;
  }
  return 'Неверное имя пользователя';
});
