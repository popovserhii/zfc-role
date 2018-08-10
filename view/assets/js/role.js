StagemRole = {
  body: $('body'),

	attachEvents: function () {
		this.attachInitTabs();
	},

  attachInitTabs: function () {
    // Remove handler from existing elements
    this.body.off('click', '.tab > a', this.initTabs);

    // Re-add event handler for all matching elements
    this.body.on('click', '.tab > a', this.initTabs);
  },

  initTabs: function () {
    var current = $(this);
    var parentCurrent = current.parent();
    var classActive = 'active';

    $('.body-content > div').hide();
    $('.body-content > div' + current.attr('href')).show();

    parentCurrent.parent().find('.' + classActive).removeClass(classActive);
    parentCurrent.addClass(classActive);

    return false;
  }
};

jQuery(document).ready(function ($) {
	StagemRole.attachEvents();

	/*$(document).on('shown.bs.modal', function (e) {
		AgereDatePicker.attachEvents(); // reattach print barcode button
	});*/

});