var tourBlocks = {
	
	test:[
		{
			element: '[data-guide="viewPartsPanel"]',
			intro:"tour test",
			position:"top"
		}
	],
	
	stepsBlock1:[
		{
			element: '#statemodebutton',
			intro: 'stat button',
			position: 'bottom'
		},
		{
			element: '#airmodebutton',
			intro: 'air button',
			position: 'bottom'
		},
		{
			element: '#cap',
			intro: 'main_menu',
			position: 'right'
		},
		{
			element: '#main_menu>li>a[data-panel="parts"]',
			intro: 'parts <br/> parts <b>just</b>',
			position: 'right'
		},
		{
			element: '#main_menu>li>a[data-panel="partsett"]',
			intro: 'settings of parts',
			position: 'right'
		},
		{
			element: '#main_menu>li>a[data-panel="content"]',
			intro: 'content of parts',
			position: 'right'
		},
		{
			element: '#main_menu>li>a[data-panel="modules"]',
			intro: 'modules of parts',
			position: 'right'
		},
		{
			element: '#main_menu>li>a[data-panel="plugins"]',
			intro: 'plugins',
			position: 'right'
		},
		{
			element: '#main_menu>li>a[data-panel="words"]',
			intro: 'words',
			position: 'right'
		},
		{
			element: '#main_menu>li>a[data-panel="settings"]',
			intro: 'site settings',
			position: 'right'
		},
		{
			element: '#main_menu>li>a[data-panel="filemanager"]',
			intro: 'filemanager',
			position: 'right'
		},
		{
			element: '#main_menu>li>a[data-panel="users"]',
			intro: 'users',
			position: 'right'
		},
		{
			element: '#main_menu>li>a[data-panel="help"]',
			intro: 'help',
			position: 'right'
		}
	],
	stepsBlock2:[
		{
			element: '.panel_parts .mmenu',
			intro: 'all pages',
			position: 'left'
		},
		{
			element: '.panel_parts .mmenu a.add:first',
			intro: 'add new page',
			position: 'right'
		},
		{
			element: '.panel_parts .mmenu a.delete:first',
			intro: 'delete page',
			position: 'right'
		}
	],
	viewMainMenu:[
		{
			element: '#cap',
			intro: 'Это <b>главное меню</b> панели управления проектом.',
			position: 'right'
		}
	],
	viewPartsPanel:[
		{
			element: '.panel_parts .mmenu',
			intro: 'Это перечень всех разделов проекта, которыми вы можете управлять.',
			position: 'top'
		}/* ,
		{
			element: '.panel_parts .mmenu a[data-get="page"]',
			intro: '<b>Название раздела</b>. Нажав на название раздела, вы поменяете текущий редактируемый раздел на тот, что выбрали и автоматически перейдете в панель редактирования контента этого раздела',
			position: 'right'
		},
		{
			element: '.panel_parts .mmenu a.add',
			intro: 'Кнопка <b>добавления</b> раздела. Вы можете добавлять разделы и подразделы спомощью этой кнопки.',
			position: 'right'
		},		
		{
			element: '.panel_parts .mmenu a.delete',
			intro: 'Кнопка <b>удаления</b> раздела. Вы можете удалить раздел, нажав на кнопку, расположенную напротив названия раздела.',
			position: 'right'
		}, */

	]
}
