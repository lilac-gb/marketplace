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
