export function constructUrl(url, params) {
  return `${url}?${(new URLSearchParams(params)).toString()}`;
}