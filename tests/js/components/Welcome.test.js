import { mount }  from 'vue-test-utils';
import { expect } from 'chai';
import Welcome    from '@/components/Welcome.vue';

describe('Welcome', () => {
  let wrapper;

  before(() => {
    wrapper = mount(Welcome);
  });

  it('renders the correct markup', () => {
    expect(wrapper.html()).contain('<div class="panel-heading">Welcome</div>');
  });
});
