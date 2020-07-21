export function timestampToDate(timestamp) {
  // function returns date in dd.mm.yyyy format
  // according to this https://stackoverflow.com/questions/847185/convert-a-unix-timestamp-to-time-in-javascript
  // timestamp should be multiplied by 1000
  let date = new Date(timestamp * 1000);
  return `${date.getDate()}.${date.getMonth() + 1}.${date.getFullYear()}`;
}

export function getFullName(user) {
  let firstName = user.first_name ? user.first_name : '';
  let lastName = user.last_name ? user.last_name : '';

  return `${firstName} ${lastName}`;
}