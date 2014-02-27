{strip}
<ul class="comments_list">
	{foreach from=$itemCommentsArray item=comment}
		{if $comment.hide eq '0'}
		<li>
			<div class="comment">
				<div class="comment_inner{if $comment.remove neq '0'} removed{/if}">

					<div class="info">
						<div>
							<a class="anchor" href="#comment_{$comment.id}" name="comment_{$comment.id}" title="Ссылка на комментарий">#</a>
							{if $comment.remove eq '0'}<a href="#" class="reply_to" data-id="{$comment.id}">Ответить</a>{/if}
						</div>
						<span class="username">{$comment.user_name|escape}</span>
						<span class="date">{$comment.comment_date} г. {$comment.comment_time}</span>
					</div>

					<span class="text">{if $comment.remove eq '0'}{$comment.comment_text}{else}Комментарий удален.{/if}</span>
				</div>

				{if isset($comment.childNodes)}
					<div class="reply">
						{include file="item_comments.tpl" itemCommentsArray=$comment.childNodes}
					</div>
				{/if}

			</div>

		</li>
		{/if}
	{/foreach}

</ul>
{/strip}