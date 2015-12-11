function updateIsReleased(target_url, id, language, is_released) {
	$.ajax({
		type : 'POST',
		url : target_url,
		dataType : 'html',
		async : false,
		data : {
			rand : Math.random(),
			id : id,
			language : language,
			is_released : is_released
		},
		beforeSend : function() {
		},
		complete : function() {
		},
		success : function(str) {
		}
	});
}

function isInt(x) {
	var y = parseInt(x);
	if (isNaN(y)) {
		return false;
	}
	return x == y && x.toString() == y.toString();
}

function multiDelete(confirmTips, form, checkboxName) {
	if (confirm(confirmTips)) {
		data = '';
		$.each($(form).find(':checkbox[name="' + checkboxName + '"]'),
				function(i, n) {
					if ($(n).attr('checked') == true
							|| $(n).attr('checked') == 'checked') {
						data += checkboxName + '=' + $(this).val() + '&';
					}
				});
		data += 'rand=' + Math.random();
		gridViewId = '#' + $(form).find('.list-view').attr('id');

		$.ajax({
			type : 'POST',
			url : $(form).attr('action'),
			dataType : 'html',
			data : data,
			beforeSend : function() {
				$(gridViewId).addClass('list-view-loading');
			},
			success : function(requestData) {
				html = '<div>' + requestData + '</div>';
				if ($('#flash_message').length === 0) {
					$('.div1').append('<div id="flash_message" class="flash-message"></div>');					
				} 
				$('#flash_message').replaceWith($('#flash_message', html));
				$(gridViewId).replaceWith($(gridViewId, html));
			}
		});
	}
}

var Sort = {
	_gridViewId : '',
	_sortContainerId : '',
	_url : '',
	toFirst : function(e) {
		this._gridViewId = $(e).parent().parent().parent().parent().parent()
				.attr('id');
		this._url = $(e).attr('href');
		this.send();
	},
	toPrevious : function(e) {
		this._gridViewId = $(e).parent().parent().parent().parent().parent()
				.attr('id');
		this._url = $(e).attr('href');
		this.send();
	},
	toNext : function(e) {
		this._gridViewId = $(e).parent().parent().parent().parent().parent()
				.attr('id');
		this._url = $(e).attr('href');
		this.send();
	},
	toLast : function(e) {
		this._gridViewId = $(e).parent().parent().parent().parent().parent()
				.attr('id');
		this._url = $(e).attr('href');
		this.send();
	},
	toSpecify : function(e, sortContainerId) {
		this._gridViewId = $(e).parent().parent().parent().parent().parent()
				.attr('id');
		this._sortContainerId = this._gridViewId + '-sort-container';
		this._url = $(e).attr('href');

		if ($('#' + this._sortContainerId)) {
			$('#' + this._sortContainerId).dialog("destroy");
			$('#' + this._sortContainerId).remove();
		}

		$('#' + this._gridViewId).after(
				'<div id="' + this._sortContainerId
						+ '" style="display:none"></div>');

		_sortContainerId = this._sortContainerId;
		$('#' + this._sortContainerId).dialog({
			autoOpen : true,
			width : 380,
			height : 80,
			close : function(event, ui) {
				$('#' + _sortContainerId).dialog("destroy");
				$('#' + _sortContainerId).remove();
			}
		});

		$.ajax({
			type : 'POST',
			url : this._url,
			dataType : 'html',
			async : false,
			data : {
				rand : Math.random()
			},
			beforeSend : function() {
				$('#' + _sortContainerId).html(
						'<img src="image/loading.gif" alt="loading" />');
			},
			complete : function() {
			},
			success : function(data) {
				$('#' + _sortContainerId).html(data);
			}
		});
	},
	send : function() {
		$.fn.yiiGridView.update(this._gridViewId, {
			url : this._url
		});
	},
	colseSpecify : function() {
		$('#' + this._sortContainerId).dialog('close');
	},
	reloadGridView : function() {
		$.fn.yiiGridView.update(this._gridViewId);
	}
};