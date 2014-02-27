<div class="order_items">
	<span>Перечень товаров</span>
	<div class="block">
		<table cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<td>ID в каталоге</td>
					<td>ID в 1С</td>
					<td>Артикул</td>
					<td>Изображение</td>
					<td>Название</td>
					<td>Цена</td>
					<td>Количество</td>
				</tr>
			</thead>
			<tbody>
				{foreach from=$itemsArray item=item}
				<tr>
					<td>{$item.item_id}</td>
					<td>{$item.item_external_id}</td>
					<td>{$item.item_articul}</td>
					<td>
						{if isset($item.not_exist)}
							<span>Товара больше не существует</span>
						{elseif isset($item.full_logo_src)}
							<img class="photo" width="50" height="50" src="{$item.full_logo_src}" />
						{else}
							<div class="no_photo"></div>
						{/if}
					</td>
					<td>{$item.item_name}</td>
					<td>{$item.item_price}</td>
					<td>{$item.item_count}</td>
				</tr>
				{/foreach}
			</tbody>
		</table>
	</div>
</div>