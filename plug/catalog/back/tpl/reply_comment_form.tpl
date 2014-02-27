<form name="reply_comment">
	<div class="block">

		<div class="field">
			<label>Имя:</label>
			<div class="padding">
				<input type="text" name="user_name" value="Администратор" />
			</div>
		</div>
	
		<div class="field">
			<label>Дата и время:</label>
			<div class="padding">
				<input type="text" readonly="readonly" name="date_time" value="{$nowDate}" />
			</div>
		</div>
	
		<div class="field editorblock">
			<textarea name="comment_text"></textarea>
		</div>
	</div>

	<div class="block">
		<button name="addReply" type="button">Сохранить и закрыть</button>
		<div class="clear"></div>
	</div>


</form>