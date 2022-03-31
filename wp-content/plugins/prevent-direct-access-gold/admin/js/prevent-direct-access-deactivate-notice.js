(function ($) {
  'use strict';
  var App = {
    cacheElements: function () {
      this.elements = {
        $deactivateLink: $('#the-list').find('[data-slug="prevent-direct-access-gold"] span.deactivate a'),
      }
    },
    bindEvents: function () {
      var self = this;
      self.elements.$deactivateLink.on('click', function (evt) {
        if (!confirm(pda_deactivate_data.message)) {
          return false;
        }
      })
    },
    init: function () {
      this.cacheElements();
      this.bindEvents();
    },
  }
  $(function () {
    App.init();
  });
})(jQuery);
