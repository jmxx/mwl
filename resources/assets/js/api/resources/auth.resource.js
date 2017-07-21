import { route } from './';

route().scope('auth')
  .get('me', '/user', {})
  .post('login', '/login', {});
