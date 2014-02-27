<form name="edit_comment">
	<div class="block">

		<div class="field">
			<label>Имя:</label>
			<div class="padding">
				<input type="text" name="user_name" value="{$comment.user_name|escape}" />
			</div>
		</div>
	
		<div class="field">
			<label>Дата и время:</label>
			<div class="padding">
				<input type="text" readonly="readonly" name="date_time" value="{$comment.comment_date} {$comment.comment_time}" />
			</div>
		</div>
	
		<div class="field editorblock">
			<textarea name="comment_text">{$comment.comment_text}</textarea>
		</div>
	</div>

	<div class="block">
		<button name="saveEdited" type="button">Сохранить и закрыть</button>
		<div class="clear"></div>
	</div>
</form>