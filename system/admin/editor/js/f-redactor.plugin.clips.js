if (!RedactorPlugins) var RedactorPlugins = {};

RedactorPlugins.clips = {
	init: function()
	{
		var modalWnd = 	'<ul class="redactorclip">'+
						'<li><a class="redactor_clip_link" data-func="loremIpsum(100)" href="#">Lorem Ipsum 100 слов</a></li>'+
						'<li><a class="redactor_clip_link" data-func="loremIpsum(200)" href="#">Lorem Ipsum 200 слов</a></li>'+
						'<li><a class="redactor_clip_link" data-func="loremIpsum(300)" href="#">Lorem Ipsum 300 слов</a></li>'+
						'<li><a class="redactor_clip_link" data-func="loremIpsum(400)" href="#">Lorem Ipsum 400 слов</a></li>'+
						'<li><a class="redactor_clip_link" data-func="loremIpsum(500)" href="#">Lorem Ipsum 500 слов</a></li>'+
						'<li><a class="redactor_clip_link" data-func="loremIpsum(500, true)" href="#">Lorem Ipsum 500 слов + изображение</a></li>'+
						'<li><a class="redactor_clip_link" data-func="textFormat()" href="#">Форматированный текст + изображение</a></li>'+
						'</ul>';
						
						
		var callback = $.proxy(function()
		{
			$('.redactorclip').find('.redactor_clip_link').each($.proxy(function(i, s)
			{
				$(s).click($.proxy(function()
				{
					eval("this."+ $( s ).attr("data-func"));
					return false;
				}, this));
			}, this));

			this.selectionSave();
			this.bufferSet();

		}, this );

		this.buttonAdd('clips', 'Вставить Lorem ipsum', function(e){
			this.modalInit('Lorem ipsum', modalWnd, 500, callback);
		});
	},
	insertClip: function(html)
	{
		this.selectionRestore();
		this.insertHtml($.trim(html));
		this.modalClose();
	},
	loremIpsum:function(maxWordCount, image) {
		var loremIpsumWordBank = new Array("lorem","ipsum","dolor","sit","amet,","consectetur","adipisicing","elit,","sed","do","eiusmod","tempor","incididunt","ut","labore","et","dolore","magna","aliqua.","enim","ad","minim","veniam,","quis","nostrud","exercitation","ullamco","laboris","nisi","ut","aliquip","ex","ea","commodo","consequat.","duis","aute","irure","dolor","in","reprehenderit","in","voluptate","velit","esse","cillum","dolore","eu","fugiat","nulla","pariatur.","excepteur","sint","occaecat","cupidatat","non","proident,","sunt","in","culpa","qui","officia","deserunt","mollit","anim","id","est","laborum.","sed","ut","perspiciatis,","unde","omnis","iste","natus","error","sit","voluptatem","accusantium","doloremque","laudantium,","totam","rem","aperiam","eaque","ipsa,","quae","ab","illo","inventore","veritatis","et","quasi","architecto","beatae","vitae","dicta","sunt,","explicabo.","nemo","enim","ipsam","voluptatem,","quia","voluptas","sit,","aspernatur","aut","odit","aut","fugit,","sed","quia","consequuntur","magni","dolores","eos,","qui","ratione","voluptatem","sequi","nesciunt,","neque","porro","quisquam","est,","qui","dolorem","ipsum,","quia","dolor","sit,","amet,","consectetur,","adipisci","velit,","sed","quia","non","numquam","eius","modi","tempora","incidunt,","ut","labore","et","dolore","magnam","aliquam","quaerat","voluptatem.","ut","enim","ad","minima","veniam,","quis","nostrum","exercitationem","ullam","corporis","suscipit","laboriosam,","nisi","ut","aliquid","ex","ea","commodi","consequatur?","quis","autem","vel","eum","iure","reprehenderit,","qui","in","ea","voluptate","velit","esse,","quam","nihil","molestiae","consequatur,","vel","illum,","qui","dolorem","eum","fugiat,","quo","voluptas","nulla","pariatur?","at","vero","eos","et","accusamus","et","iusto","odio","dignissimos","ducimus,","qui","blanditiis","praesentium","voluptatum","deleniti","atque","corrupti,","quos","dolores","et","quas","molestias","excepturi","sint,","obcaecati","cupiditate","non","provident,","similique","sunt","in","culpa,","qui","officia","deserunt","mollitia","animi,","id","est","laborum","et","dolorum","fuga.","harum","quidem","rerum","facilis","est","et","expedita","distinctio.","Nam","libero","tempore,","cum","soluta","nobis","est","eligendi","optio,","cumque","nihil","impedit,","quo","minus","id,","quod","maxime","placeat,","facere","possimus,","omnis","voluptas","assumenda","est,","omnis","dolor","repellendus.","temporibus","autem","quibusdam","aut","officiis","debitis","aut","rerum","necessitatibus","saepe","eveniet,","ut","et","voluptates","repudiandae","sint","molestiae","non","recusandae.","itaque","earum","rerum","hic","tenetur","a","sapiente","delectus,","aut","reiciendis","voluptatibus","maiores","alias","consequatur","aut","perferendis","doloribus","asperiores","repellat");
		
		var minWordCount = maxWordCount-2;
		
		var randy = Math.floor(Math.random()*(maxWordCount - minWordCount)) + minWordCount;
		var ret = "";
		for(i = 0; i < randy; i++) {
			var newTxt = loremIpsumWordBank[Math.floor(Math.random() * (loremIpsumWordBank.length - 1))];
			if (ret.substring(ret.length-1,ret.length) == "." || ret.substring(ret.length-1,ret.length) == "?") {
				newTxt = newTxt.substring(0,1).toUpperCase() + newTxt.substring(1, newTxt.length);
			}
			ret += " " + newTxt;
		}
		
		var html = ret.substring(0,ret.length-1);
		
		if( image!=undefined && typeof(image)==='boolean' && image===true){
			html = '<img src="/system/admin/editor/images/image.png" style="float:left; margin:0px 5px 5px 0px; width:300px;" />' + html;
		}
		
		this.insertClip(  html   );

	},
	textFormat:function(){
		var html = '<p><span style="font-variant: inherit; font-size: 50px;"><em>Q</em></span><span style="color: rgb(0, 0, 0);font-size: 20px;"><em>uaerat cupiditate dolore maiores dolores</span> <span style="color: rgb(0, 0, 0);font-size: 20px;">quam</span> <span style="color: rgb(242, 195, 20);font-size: 20px;">est exercitation quis Nam distinctio</span></em>.</p><p><a></a></p><p><img src="/admin/editor/images/image.png" style="float: left; margin: 0px 5px 5px 0px; width: 300px;"> ipsum, est est, in ab et omnis dolores quia voluptas eum natus cum Nam adipisci dignissimos aperiam dolor ipsam magnam nisi veniam, dolore odio sequi quibusdam optio, non sint magna dignissimos et eius dolor rerum voluptatem ad ut odio voluptatem, velit, ea qui ut quas quo totam repellendus.<strong> Distinctio</strong>. Quaerat eius suscipit animi, sunt et quibusdam non aute non nulla aspernatur in dolor non rem eius amet, dolor at quam reprehenderit sequi totam repellendus. Deleniti sunt ipsam iure tempore, commodo nostrud qui <del>officia</del> sed perspiciatis, hic dolores impedit, eum numquam doloribus enim consequatur velit est laudantium, nostrud velit,<em> expedita id aut dicta fuga. Id odio totam sit,</em> elit, laudantium, architecto iste quia ut consectetur, ducimus, dolore cillum qui ipsa, dolorum distinctio. Iure cupidatat sint distinctio.</p><hr><p>ipsum, est est, in ab et omnis dolores quia voluptas eum natus cum Nam adipisci dignissimos aperiam dolor ipsam magnam nisi veniam, dolore odio sequi quibusdam optio, non sint magna dignissimos et eius dolor rerum voluptatem ad ut odio voluptatem, velit, ea qui ut quas quo totam repellendus.<strong style="line-height: 1.45em; font-style: inherit; font-variant: inherit;"> Distinctio</strong>. Quaerat eius suscipit animi, sunt et quibusdam non aute non nulla aspernatur in dolor non rem eius amet, dolor at quam reprehenderit sequi totam repellendus. Deleniti sunt ipsam iure tempore, commodo nostrud qui <del style="line-height: 1.45em; font-style: inherit; font-variant: inherit;">officia</del> sed perspiciatis, hic dolores impedit, eum numquam doloribus enim consequatur velit est laudantium, nostrud velit,<em style="line-height: 1.45em; font-variant: inherit;"> expedita id aut dicta fuga. Id odio totam sit,</em> elit, laudantium, architecto iste quia ut consectetur, ducimus, dolore cillum qui ipsa, dolorum distinctio. Iure cupidatat sint distinctio.</p><hr><h1>Amet, voluptatem. <em>Do beatae sit repellendus</em>. In sunt, sit porro dolorem exercitationem unde vero doloribus quis placeat.</h1>';
		
		this.insertClip(  html   );
	}
};

