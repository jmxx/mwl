import { route } from './';

route().scope('auth')
  .get('me', '/user', {})
  .post('logout', '/logout', {})
  .post('login', '/login', {});
