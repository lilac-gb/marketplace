import { extend } from 'vee-validate';
import { required, email, confirmed } from 'vee-validate/dist/rules';

extend('required', {
  ...required,
  message: 'Это поле обязательно для заполнения',
});

extend('confirmed', {
  ...confirmed,
  message: 'Поля должны совпадать',
});

extend('email', {
  ...email,
  message: 'Неправильный email',
});

extend('min', {
  validate(value, { length }) {
    return value.length >= length;
  },
  params: ['length'],
  message: 'Минимальное количествоо знаков {length}',
});

extend('max', {
  validate(value, { length }) {
    return value.length <= length;
  },
  params: ['length'],
  message: 'Максимальное количествоо знаков {length}',
});

extend('name', (name) => {
  const regExp = /^[\sa-zA-Zа-яА-я]+$/;

  if (!regExp.test(name)) {
    return 'Это поле не может содержать цифры и символы, кроме "-"';
  }

  return true;
});

extend('username', (name) => {
  const regExp = /^[\sa-zA-Z0-9-]+$/;

  if (!regExp.test(name)) {
    return 'Имя пользователя не может содержать кирилицу и символы кроме "-"';
  }

  return true;
});

extend('atLeastOneDigAndSpec', (name) => {
  const regExp = /^(?=.*[0-9])(?=.*[!@#$%^&*])/;

  if (!regExp.test(name)) {
    return 'Пароль должен содержать хотябы одну цифру и хотябы один спец символ (!@#$%^&*)';
  }

  return true;
});

extend('atLeastLLetter', (name) => {
  const regExp = /^(?=.*[a-zа-я])/;

  if (!regExp.test(name)) {
    return 'Пароль должен содержать хотябы одну строчную букву';
  }

  return true;
});

extend('atLeastULetter', (name) => {
  const regExp = /^(?=.*[A-ZА-Я])/;

  if (!regExp.test(name)) {
    return 'Пароль должен содержать хотябы одну заглавную букву';
  }

  return true;
});
