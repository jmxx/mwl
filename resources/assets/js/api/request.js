import { resources, resource } from './resources';

export default function request(scope = 'default') {
  return resource(scope);
}
