$(function () {

	var route = {
		new_message: '?r=messenger/new-message',
		delete_message: '?r=messenger/delete-message',
	};

	var methods = {
		new_message: 'new_message',
		delete_message: 'delete_message'
	};

	var csrfToken = $('meta[name="csrf-token"]').attr("content");
	var user = $('.dropdown-toggle')[0].innerText;
	var attaches = [];

	var conn = new WebSocket('ws://localhost:3030');

	/**
	 *
	 * @param url
	 * @param data
	 * @param error
	 * @param done
	 */
	var ajaxRequest = function (url, data, error, done) 
	{
		$.ajax(
		{
			url: url,
			method: 'POST',
			data: data,
			dataType: 'json'
		}).error(function (d) 
		{
			error(d)
		}).done(function (d) 
		{
			done(d);
		});
	};

	var waitForConnection = function (callback, interval) 
	{
	    if (conn.readyState === 1) {
	        callback();
	    } else {
	        var that = this;
	        // optional: implement backoff for interval here
	        setTimeout(function () {
	            waitForConnection(callback, interval);
	        }, interval);
	    }
	};

	/**
	 * Результат открытия сокета
	 * @param result
	 */
	conn.onopen = function (result) 
	{
		console.log(result);
	};

	/**
	 * Рассылка сообщений подписчикам
	 * @param result
	 */
	conn.onmessage = function (result) 
	{
		var json = JSON.parse(result.data);
		var messageId = null;
		var content = null;
		var userName = null;
		var createdAt = null;

		if (json !== null) 
		{
			switch (json['type']) 
			{
				case methods.new_message:
					messageId = json['messageId'];
					content = json['content'];
					userName = json['userName'];
					createdAt = json['createdAt'];

					addMessages(messageId, content, userName, createdAt);

					break;

				case methods.delete_message:
					messageId = json['messageId'];
					deleteMessage(messageId);

					break;
			}
		}
	};

	/**
	 * Отправка сообщения в чат
	 */
	$('#send-message').on('click', function () 
	{
		var input = $('#message-text');
		var text = input.val();
		var content = '';
		if (text.length > 0) 
		{
			var attachToString = '';
			$.each(attaches, function (key, value) 
			{
				switch (value.type) 
				{
					case 'image':
						attachToString += '<img src="messenger-master/web/storage/' + value.content + '">';
						break;
					case 'video':
						attachToString += '<iframe src="https://www.youtube.com/embed/' + value.content + '" frameborder="0" allowfullscreen></iframe>';
						break;
					case 'link':
						attachToString += '<p><a href="' + value.content + '">Ссылка</a></p>';
						break;
				}
			});

			content = text + attachToString;

			ajaxRequest(
				route.new_message,
				{_csrf: csrfToken, content: content, userName: user},
				function (error) 
				{
					console.log(error);
				},
				function (message) 
				{
					waitForConnection(function () 
					{
				        conn.send(JSON.stringify({
							type: methods.new_message,
							content: content,
							userName: user,
							messageId: message.id,
							createdAt: message.createdAt
						}));
				        if (typeof callback !== 'undefined') 
				        {
				          callback();
				        }
				    }, 1000);

					input.val('');

					addMessages(message.id, text, user, message.createdAt);
				}
			);
		}
	});

	/**
	 * Удаление сообщения из чата
	 */
	$('.messages').on('click', '#delete', function () 
	{
		var message = $(this).parent('.message');
		var messageId = message.attr('id');

		ajaxRequest(
			route.delete_message,
			{_csrf: csrfToken, messageId: messageId},
			function (error) 
			{
				console.log(error);
			},
			function () 
			{
				conn.send(JSON.stringify(
				{
					type: methods.delete_message,
					messageId: messageId
				}));

				deleteMessage(messageId);
			}
		);

		return false;
	});


	/**
	 *
	 * @param id
	 * @param content
	 * @param userName
	 * @returns {string}
	 * @param createdAt
	 */
	function addMessages(id, content, userName, createdAt) 
	{
		var attachToString = '';
		$.each(attaches, function (key, value) 
		{
			switch (value.type) 
			{
				case 'image':
					attachToString += '<img src="messenger-master/web/storage/' + value.content + '">';
					break;
				case 'video':
					attachToString += '<iframe src="https://www.youtube.com/embed/' + value.content + '" frameborder="0" allowfullscreen></iframe>';
					break;
				case 'link':
					attachToString += '<p><a href="' + value.content + '">Ссылка</a></p>';
					break;
			}
		});

		var deleteLink = user === userName ? '<a href="#" id="delete">Удалить</a>' : '';
		var msg = $('<div class="message" id="' + id + '"><h4>' + userName + ' says:</h4>' + content + attachToString + '<br><br><small>' + createdAt + '<br>' + deleteLink + '</div>');

		$('.messages').append(msg);
		msg.hide().fadeIn(500);

		attaches = [];
		$('#attaches').text('');
	}

	function deleteMessage(messageId)
	{
		var message = $('#' + messageId);

		message.slideUp();
	}

	/**
	 * Вызов диалога для загрузки изображений
	 */
	$('.add-pictures-link').on('click', function () 
	{
		$('.add-pictures-dialog').fadeIn();
		$('.attach-menu').fadeOut();
		return false;
	});

	/**
	 * Вызов диалога для добавления видео
	 */
	$('.add-video-link').on('click', function () 
	{
		$('.add-video-dialog').fadeIn();
		return false;
	});

	$('.add-url-link').on('click', function () 
	{
		$('.add-url-dialog').fadeIn();
		return false;
	});

	/**
	 * Закрытие диалогового окна
	 */
	$('.close-dialog').on('click', function () 
	{
		$('.dialog').fadeOut();
		return false;
	});

	/**
	 *
	 */
	$('.add-video').on('click', function () 
	{
		var video_url = $('#video-url').val();
		var youtubeId = youTubeGetId(video_url);

		if (youtubeId) {
			attaches.push({
				type: 'video',
				content: youtubeId
			});

			$('#video-form').hide();
			$('#success_video').hide().fadeIn().text('Видео было добавлено к сообщению');
		} else {
			alert('Убедитесь в правильности ссылки на видео');
		}

		return false;
	});

	$('.add-url').on('click', function () 
	{
		var url = $('#url').val();

		if (validationUrl(url)) {
			attaches.push({
				type: 'link',
				content: url
			});

			$('#url-form').hide();
			$('#success_url').hide().fadeIn().text('Ссылка добавлена к сообщению');
		} else {
			alert('Убедитесь в правильности ссылки');
		}

		return false;
	});

	/**
	 * Прослушивание события fileuploaded,
	 * для определения какие файлы были загружены
	 */
	$('input[name=\'UploadForm[file]\']').on('fileuploaded', function (event, data) 
	{
		var response = data.response;

		attaches.push(
		{
			type: 'image',
			content: response
		});

		$('#attaches').append('<img src="messenger-master/web/storage/' + response + '">');
	});

	/**
	 *
	 * @param url
	 * @returns {*|Array|{index: number, input: string}}
	 */
	function youTubeGetId(url) 
	{
		var p = /^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/;
		var video = (url.match(p)) ? RegExp.$1 : false;

		if (video) 
		{
			return video;
		} 
		else 
		{
			return false;
		}
	}

	function validationUrl(url) 
	{
		var p = /^(http[s]?:\/\/){0,1}(www\.){0,1}[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,5}[\.]{0,1}/;
		var link = (url.match(p)) ? RegExp.$1 : false;

		if (link) 
		{
			return link;
		} 
		else 
		{
			return false;
		}
	}
});