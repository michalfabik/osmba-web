/**
 * Internationalization: czech language
 *
 * Depends on jWYSIWYG, $.wysiwyg.i18n
 *
 * By: deepj on github.com
 */
(function ($) {
	if (undefined === $.wysiwyg) {
		throw "lang.cs.js depends on $.wysiwyg";
	}
	if (undefined === $.wysiwyg.i18n) {
		throw "lang.cs.js depends on $.wysiwyg.i18n";
	}

	$.wysiwyg.i18n.lang.cs = {
		controls: {
			"Bold": "Podebljano",
			"Colorpicker": "Izbor boje",
			"Copy": "Kopiraj",
			"Create link": "Kreiraj vezu",
			"Cut": "Izreži",
			"Decrease font size": "Smanji font",
			"Fullscreen": "Cijeli ekran",
			"Header 1": "Naslov 1",
			"Header 2": "Naslov 2", "Header 3": "Naslov 3",
			"View source code": "Prikaži izvorni kod",
			"Increase font size": "Povećaj font",
			"Indent": "Uvuci tekst",
			"Insert Horizontal Rule": "Umetni horizontalnu liniju",
			"Insert image": "Umetni sliku",
			"Insert Ordered List": "Umetni numerisani spisak",
			"Insert table": "Umetni tabelu",
			"Insert Unordered List": "Umetni spisak",
			"Italic": "Kurziv",
			"Justify Center": "Poravnaj na sredinu",
			"Justify Full": "Poravnaj u blok",
			"Justify Left": "Poravnaj lijevo",
			"Justify Right": "Poravnaj desno",
			"Left to Right": "Slijeva nadesno",
			"Outdent": "Izvuci tekst",
			"Paste": "Zalijepi",
			"Redo": "Ponovi",
			"Remove formatting": "Ukloni formatiranje",
			"Right to Left": "Zdesna nalijevo",
			"Strike-through": "Precrtano",
			"Subscript": "Indeks",
			"Superscript": "Eksponent",
			"Underline": "Podvučeno",
			"Undo": "Poništi"
		},

		dialogs: {
			// for all
			"Apply": "Primijeni",
			"Cancel": "Otkaži",

			colorpicker: {
				"Colorpicker": "Izbor boje",
				"Color": "Boja"
			},

			fileManager: {
				"file_manager": "Upravitelj fajlova",
				"upload_title": "Uploaduj fajl",
				"rename_title": "Preimenuj fajl",
				"remove_title": "Izbriši fajl",
				"mkdir_title": "Kreiraj fasciklu",
				"upload_action": "Uploaduj novi fajl u trenutnu fasciklu",
				"mkdir_action": "Kreiraj novu fasciklu",
				"remove_action": "Izbriši ovaj fajl",
				"rename_action": "Preimenuj ovaj fajl" ,
				"delete_message": "Jeste li sigurni da želite izbrisati ovaj fajl?",
				"new_directory": "Nova fascikla",
				"previous_directory": "Vrati se u prethodnu fasciklu",
				"rename": "Preimenuj",
				"select": "Izaberi",
				"create": "Kreiraj",
				"submit": "Pošalji",
				"cancel": "Otkaži",
				"yes": "Da",
				"no": "Ne"
			},

			image: {
				"Insert Image": "Umetni sliku",
				"Preview": "Pretpregled",
				"URL": "Link",
				"Title": "Naziv",
				"Description": "Opis",
				"Width": "Širina",
				"Height": "Visina",
				"Original W x H": "Izvorna širina i visina",
				"Float": "Plutanje",
				"None": "Bez plutanja",
				"Left": "Lijevo",
				"Right": "Desno",
				"Select file from server": "Izaberi fajl sa servera"
			},

			link: {
				"Insert Link": "Umetni link",
				"Link URL": "Link",
				"Link Title": "Tekst linka",
				"Link Target": "Cilj linka"
			},

			table: {
				"Insert table": "Umetni tabelu",
				"Count of columns": "Broj kolona",
				"Count of rows": "Broj redova"
			}
		}
	};
})(jQuery);
