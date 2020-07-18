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

extend('username', (name) => {
  const regExp = /^[\sa-zA-Zа-яА-я0-9-]+$/;

  if (!regExp.test(name)) {
    return 'Имя пользователя не может содержать символов, кроме "-"';
  }

  return true;
});
