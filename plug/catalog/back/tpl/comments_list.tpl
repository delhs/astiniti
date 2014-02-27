<ul class="comments_list">
	{foreach from=$comments item=comment}
		
		<li>
			<div class="comment">
				<div class="comment_inner">

					<div class="toolbar">
						<div class="tool">
							<input type="checkbox" name="hide" id="hide_{$comment.id}" data-id="{$comment.id}" value="0" {if $comment.hide eq '0'}checked="checked"{/if} />
							<label for="hide_{$comment.id}">Публиковать</label>
						</div>

						<div class="tool">
							<span class="icon edit" data-id="{$comment.id}"></span>
							<a href="#" class="edit" data-id="{$comment.id}">Редактировать</a>
						</div>

						<div class="tool">
							<span class="icon delete" data-id="{$comment.id}"></span>
							<a href="#" class="delete" data-id="{$comment.id}">Удалить</a>
						</div>

						<div class="tool">
							<span class="icon ireply" data-id="{$comment.id}"></span>
							<a href="#" class="ireply" data-id="{$comment.id}">Ответить</a>
						</div>

						<div class="clear"></div>
					</div>

					<div class="info">
						<span class="username">{$comment.user_name|escape}</span>
						<span class="date">{$comment.comment_date} г. {$comment.comment_time}</span>
					</div>

					<span class="text">{$comment.comment_text}</span>

					<div class="clear"></div>
				</div>

				{if isset($comment.childNodes)}
					<div class="reply">
						{include file="comments_list.tpl" comments=$comment.childNodes}
					</div>
				{/if}

			</div>

		</li>
		
	{/foreach}

</ul>