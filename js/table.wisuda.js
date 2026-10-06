
/*
 * Editor client script for DB table wisuda
 * Created by http://editor.datatables.net/generator
 */

addEventListener("DOMContentLoaded", function () {
	var editor = new DataTable.Editor( {
		ajax: 'php/table.wisuda.php',
		table: '#wisuda',
		fields: [
			{
				"label": "seksi:",
				"name": "seksi"
			},
			{
				"label": "job:",
				"name": "job"
			},
			{
				"label": "pic:",
				"name": "pic"
			},
			{
				"label": "persentase:",
				"name": "persentase"
			}
		]
	} );

	var table = new DataTable('#wisuda', {
		ajax: 'php/table.wisuda.php',
		columns: [
			{
				"data": "seksi"
			},
			{
				"data": "job"
			},
			{
				"data": "pic"
			},
			{
				"data": "persentase"
			}
		],
		layout: {
			topStart: {
				buttons: [
					{ extend: 'create', editor: editor },
					{ extend: 'edit', editor: editor },
					{ extend: 'remove', editor: editor }
				]
			}
		},
		select: true
	});
});

