import { route } from './';

route().scope('users')
  .post('create', '/register', {});
