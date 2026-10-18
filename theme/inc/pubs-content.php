<?php
/**
 * Публикации адвокатов коллегии в СМИ.
 *
 * Ссылки собраны по анонсам канала коллегии и проверены: страница
 * открывается и в тексте есть упоминание коллегии или адвоката.
 *
 * @package MPartners
 */

/**
 * Материалы для рубрики «Публикации в СМИ».
 *
 * @return array
 */
function mp_pubs_content() {
	return [
		[
			'title' => 'Превентивная защита: как предпринимателю снизить риски обвинения в мошенничестве',
			'outlet' => 'Biz360',
			'date' => '2025-08-13',
			'url' => 'https://biz360.ru/materials/preventivnaya-zashchita-kak-predprinimatelyu-snizit-riski-obvineniya-v-moshennichestve/',
		],
		[
			'title' => 'Как первым лицам медорганизаций обезопасить себя при госзакупках',
			'outlet' => 'Медвестник',
			'date' => '2025-09-17',
			'url' => 'https://medvestnik.ru/content/news/Kak-pervym-licam-medorganizacii-obezopasit-sebya-pri-goszakupkah.html',
		],
		[
			'title' => 'Подарок не должен стать взяткой: Что можно, а что нельзя дарить педагогам на День Учителя',
			'outlet' => 'Комсомольская правда',
			'date' => '2025-10-03',
			'url' => 'https://www.kp.ru/daily/27722/5149579/',
		],
		[
			'title' => 'Как малому бизнесу избежать уголовного дела по 159-й статье',
			'outlet' => 'РБК Pro',
			'date' => '2025-10-08',
			'url' => 'https://pro.rbc.ru/demo/68e22cb49a79477ce6b33111?ysclid=mghf8gp9dx786843198',
		],
		[
			'title' => 'Вывести на чистую воду: как провести в компании внутреннее расследование',
			'outlet' => 'Biz360',
			'date' => '2025-10-13',
			'url' => 'https://biz360.ru/materials/vyvesti-na-chistuyu-vodu-kak-provesti-v-kompanii-vnutrennee-rassledovanie/',
		],
		[
			'title' => 'Интервью с Екатериной Беспаловой: как корни, дети и спорт формируют сильного лидера',
			'outlet' => 'Legal Bulletin',
			'date' => '2025-10-17',
			'url' => 'https://legalbulletin.online/intervju-s-ekaterinoj-bespalovoj-kak-korni-deti-i-sport-formirujut-silnogo-lidera/',
		],
		[
			'title' => 'Как обман становился технологичнее — от древних афер до современных NFC-угроз',
			'outlet' => 'vc.ru',
			'date' => '2025-10-24',
			'url' => 'https://vc.ru/id3949016/2287457-kak-obman-stanovilsya-tehnologichnee-ot-drevnih-afer-do-sovremennyh-nfc-ugroz',
		],
		[
			'title' => 'Спасти и сохранить: как предпринимателю защитить личное и семейное имущество от ареста',
			'outlet' => 'Biz360',
			'date' => '2025-11-26',
			'url' => 'https://biz360.ru/materials/spasti-i-sokhranit-kak-predprinimatelyu-zashchitit-lichnoe-i-semeynoe-imushchestvo-ot-aresta/',
		],
		[
			'title' => 'Как соблюсти законность в тендерах и конкурсах на поставку лекарств',
			'outlet' => 'Медвестник',
			'date' => '2025-12-24',
			'url' => 'https://medvestnik.ru/content/news/kak-soblusti-zakonnost-v-tenderah-i-konkursah-na-postavku-lekarstv.html',
		],
		[
			'title' => 'Коллегия M-PARTNERS — среди призеров премии Федеральной палаты адвокатов',
			'outlet' => 'Федеральная палата адвокатов',
			'date' => '2026-02-26',
			'url' => 'https://fparf.ru/news/fpa/28-prizerov-v-9-nominatsiyakh/',
		],
		[
			'title' => 'Адвокат Беспалова разъяснила россиянам, как быть при вымогательстве взятки',
			'outlet' => 'Южный федеральный',
			'date' => '2026-03-19',
			'url' => 'https://u-f.ru/news/society/u9/2026/03/17/413600',
		],
		[
			'title' => 'Адвокат Беспалова объяснила, как правильно себя вести при обыске',
			'outlet' => 'Южный федеральный',
			'date' => '2026-04-13',
			'url' => 'https://u-f.ru/news/criminal/u24974/2026/04/06/414849',
		],
		[
			'title' => 'Дроп: что значит на молодежном сленге и чем это грозит по закону',
			'outlet' => 'Комсомольская правда',
			'date' => '2026-04-29',
			'url' => 'https://www.kp.ru/edu/molodezhnyj-sleng/drop/',
		],
		[
			'title' => 'Екатерина Беспалова: если к вам пришли в 6 утра — вы уже проиграли — Basis Press | Бизнес',
			'outlet' => 'Basis.press',
			'date' => '2026-04-30',
			'url' => 'http://basis.press/interview/advocate.html',
		],
		[
			'title' => 'Массажное кресло как взятка, аутсорсинг как прикрытие: как бизнесу не попасть под статью 291 УК, когда подкупом признают даже ортопедический матрас?',
			'outlet' => 'RuNews24',
			'date' => '2026-05-12',
			'url' => 'https://runews24.ru/business/05/05/2026/massazhnoe-kreslo-kak-vzyatka-autsorsing-kak-prikryitie-kak-biznesu-ne-popast-pod-statyu-291-uk-kogda-podkupom-priznayut-dazhe-ortopedicheskij-matras?ysclid=moty53qquw661592578',
		],
		[
			'title' => 'Какие ошибки в бухгалтерии приводят не к штрафу, а к уголовной ответственности',
			'outlet' => 'Инвест-Форсайт',
			'date' => '2026-06-05',
			'url' => 'https://www.if24.ru/oshibki-v-buhgalterii/',
		],
		[
			'title' => 'Шоу-дебаты «За / Против»: суд присяжных',
			'outlet' => 'Российское общество «Знание»',
			'date' => '2026-07-09',
			'url' => 'https://znanierussia.ru/library/video/shou-debaty-za-protiv-19-vypusk-sud-prisyazhnyh-5341',
		],
		[
			'title' => 'Снижение возраста уголовной ответственности до 12 лет: аргументы сторонников и противников',
			'outlet' => 'Legal Bulletin',
			'date' => '2026-07-10',
			'url' => 'https://legalbulletin.online/snizhenie-vozrasta-ugolovnoj-otvetstvennosti-do-12-let-argumenty-storonnikov-i-protivnikov/',
		],
	];
}
