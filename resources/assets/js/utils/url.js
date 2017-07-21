export function replace(url, params = {}, deleteParams = true) {
  return url.replace(/:(\w+)/g, (match, backreference) => {
    // Instead of setting to null, we keep the match string
    // and only matched params will be replaced
    let replacement = params[backreference] || `:${backreference}`;

    if (replacement && deleteParams) {
      delete params[backreference];
    }

    return replacement;
  });
};
