!function (ng, $, Module) {

	Module.directive('bsModal', function () {
		var TEMPLATE = $('<div class="modal fade">' +
			'<div class="modal-dialog">' +
				'<div class="modal-content">' +
					'<div class="modal-body">' +
						'<div class="modal-loading"></div>' +
					'</div>' +
					'<div class="modal-footer">' +
						'<button class="btn btn-default" data-dismiss="modal">Schließen</button>' +
					'</div>' +
				'</div>' +
			'</div>' +
		'</div>');

		function createModal(url) {
			var modal = TEMPLATE.clone().appendTo('body');
			modal.find('.modal-body').load(url);
			return modal;
		}

		return {
			link : function (scope, element, attrs) {
				var modal;
				element.click(function () {
					if (!modal) modal = createModal(attrs.bsModal);
					modal.modal('show');
				});
				element.on('$destroy', function () { modal && modal.remove(); });
			}
		}
	});

	Module.directive('bsTooltip', function () {
		return {
			link : function (scope, element, attrs) {
				element.tooltip({ title : attrs.bsTooltip });
			}
		};
	});

}(angular, jQuery, BTW);
