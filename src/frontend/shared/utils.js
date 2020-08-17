export function timestampToDate(timestamp, time = false) {
  // function returns date in dd.mm.yyyy format
  // according to this https://stackoverflow.com/questions/847185/convert-a-unix-timestamp-to-time-in-javascript
  // timestamp should be multiplied by 1000
  let date = new Date(timestamp * 1000);
  if (!time) {
    return `${date.getDate()}.${pad(
      date.getMonth() + 1
    )}.${date.getFullYear()}`;
  } else {
    return `${date.getDate()}.${pad(
      date.getMonth() + 1
    )}.${date.getFullYear()} в ${date.getHours()}:${date.getMinutes()}`;
  }
}

export function declinationName(count = 0, words = []) {
  const cases = [2, 0, 1, 1, 1, 2];
  return words[
    count % 100 > 4 && count % 100 < 20 ? 2 : cases[Math.min(count % 10, 5)]
    ];
}

function pad(month) {
  return month < 10 ? `0${month}` : month;
}

export function getFullName(user) {
  let firstName = user.first_name ? user.first_name : '';
  let lastName = user.last_name ? user.last_name : '';

  return `${firstName} ${lastName}`;
}

export function toCurrency(number) {
  const formatter = new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'EUR',
  });

  return formatter.format(number);
}
