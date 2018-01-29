require('../css/app.scss');
require('bootstrap-sass');

// static assets
require('../images/testio-dark.png');
require('../images/wv1.jpg');

import PerfectScrollbar from 'perfect-scrollbar';
if (document.querySelectorAll('.scrollable').length > 0) {
    const ps = new PerfectScrollbar('.scrollable');
}
