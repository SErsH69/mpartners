<?php

add_action(
	'acf/init',
	function () {
		acf_add_options_page(
			[
				'page_title' => 'РќР°СЃС‚СЂРѕР№РєРё СЃР°Р№С‚Р°',
				'menu_slug'  => 'site_settings',
				'position'   => '',
				'redirect'   => false,
			]
		);
	}
);

add_action(
	'acf/include_fields',
	function () {
		if ( ! function_exists( 'acf_add_local_field_group' ) ) {
			return;
		}

		acf_add_local_field_group(
			[
				'key'                   => 'group_6581ccdeed7e7',
				'title'                 => 'Main',
				'fields'                => [
					[
						'key'           => 'field_modal',
						'label'         => 'РљРЅРѕРїРєР° РїРµСЂРІРѕР№ РјРѕРґР°Р»РєРё',
						'name'          => 'modal_link',
						'type'          => 'link',
						'default_value' => '#modal',
					],
					[
						'key'   => 'field_6581ccdfd4a8f',
						'label' => 'EMail',
						'name'  => 'general_email',
						'type'  => 'text',
						'default_value' => 'admin@dka-media.ru',
					],
					[
						'key'   => 'field_6581cd20d4a931',
						'label' => 'Whatsapp',
						'name'  => 'general_whatsapp',
						'type'  => 'url',
						'default_value' => 'https://wa.me/79018703064',
					],
					[
						'key'   => 'field_6581cd20d4a91',
						'label' => 'Telegram',
						'name'  => 'general_telegram',
						'type'  => 'url',
						'default_value' => 'https://t.me/camzink',
					],
					[
						'key'   => 'field_6581cd6ad4a94',
						'label' => 'Address',
						'name'  => 'general_address',
						'type'  => 'text',
						'default_value' => 'Рі. РњРѕСЃРєРІР°, СѓР». РќРѕРІРѕРґРјРёС‚СЂРѕРІСЃРєР°СЏ, 2Рє1',
					],
					[
						'key'           => 'field_6581cd3dd4a92',
						'label'         => 'РњР°СЃРєР° С‚РµР»РµС„РѕРЅР°',
						'name'          => 'general_phone_mask',
						'type'          => 'text',
						'default_value' => '+79018703064',
					],
					[
						'key'           => 'field_6581cd59d4a93',
						'label'         => 'РўРµР»РµС„РѕРЅ',
						'name'          => 'general_phone',
						'type'          => 'text',
						'default_value' => '+7 (901) 870-30-64',
					],
					[
						'key'   => 'field_6581cd7fd4a95',
						'label' => 'Copyrights',
						'name'  => 'general_copyright',
						'type'  => 'text',
					],
					[
						'key'   => 'field_footer_form_title',
						'label' => 'Footer: Р—Р°РіРѕР»РѕРІРѕРє С„РѕСЂРјС‹',
						'name'  => 'footer_form_title',
						'type'  => 'text',
						'default_value' => '( РћРЎРўРђР›РРЎР¬ Р’РћРџР РћРЎР«? )',
					],
					[
						'key'   => 'field_footer_form_icon_svg',
						'label' => 'Footer: РРєРѕРЅРєР° (SVG РєРѕРґ)',
						'name'  => 'footer_form_icon_svg',
						'type'  => 'textarea',
						'instructions' => 'Р’СЃС‚Р°РІСЊ SVG (Р±РµР· <script>).',
					],
					[
						'key'   => 'field_footer_form_shortcode',
						'label' => 'Footer: РЁРѕСЂС‚РєРѕРґ С„РѕСЂРјС‹',
						'name'  => 'footer_form_shortcode',
						'type'  => 'text',
						'instructions' => 'РќР°РїСЂРёРјРµСЂ: [contact-form-7 id="123" title="Footer form"]',
					],
					[
						'key'   => 'field_footer_brand_text',
						'label' => 'Footer: Р‘РѕР»СЊС€РѕР№ С‚РµРєСЃС‚ СЃР»РµРІР°',
						'name'  => 'footer_brand_text',
						'type'  => 'text',
						'default_value' => 'DKA.MEDIA',
					],
					[
						'key'   => 'field_footer_contact_photo',
						'label' => 'Footer: Р¤РѕС‚Рѕ РєРѕРЅС‚Р°РєС‚Р°',
						'name'  => 'footer_contact_photo',
						'type'  => 'image',
						'return_format' => 'array',
						'preview_size'  => 'thumbnail',
						'library'       => 'all',
					],
					[
						'key'   => 'field_footer_contact_name',
						'label' => 'Footer: РРјСЏ РєРѕРЅС‚Р°РєС‚Р°',
						'name'  => 'footer_contact_name',
						'type'  => 'text',
					],
					[
						'key'   => 'field_footer_contact_position',
						'label' => 'Footer: Р”РѕР»Р¶РЅРѕСЃС‚СЊ/СЂРѕР»СЊ',
						'name'  => 'footer_contact_position',
						'type'  => 'text',
					],
					[
						'key'   => 'field_footer_contact_note',
						'label' => 'Footer: РўРµРєСЃС‚ СЃРїСЂР°РІР° (РѕРїРёСЃР°РЅРёРµ)',
						'name'  => 'footer_contact_note',
						'type'  => 'textarea',
					],
				],
				'location'              => [
					[
						[
							'param'    => 'options_page',
							'operator' => '==',
							'value'    => 'site_settings',
						],
					],
				],
				'menu_order'            => 0,
				'position'              => 'normal',
				'style'                 => 'default',
				'label_placement'       => 'top',
				'instruction_placement' => 'label',
				'active'                => true,
				'show_in_rest'          => 0,
			]
		);
	}
);



add_action(
	'acf/include_fields',
	function () {
		if ( ! function_exists( 'acf_add_local_field_group' ) ) {
			return;
		}

		acf_add_local_field_group([
			'key' => 'group_cases_fields',
			'title' => 'Р”Р°РЅРЅС‹Рµ РєРµР№СЃР°',
			'location' => [
				[
					[
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'cases',
					],
				],
			],
			'fields' => [

				[
					'key' => 'field_case_preview_img',
					'label' => 'РџСЂРµРІСЊСЋ РёР·РѕР±СЂР°Р¶РµРЅРёРµ',
					'name' => 'case_preview_img',
					'type' => 'image',
					'return_format' => 'array',
					'wrapper' => ['width' => '30'],
				],

				[
					'key' => 'field_case_short_description',
					'label' => 'РљСЂР°С‚РєРѕРµ РѕРїРёСЃР°РЅРёРµ',
					'name' => 'case_short_description',
					'type' => 'textarea',
					'rows' => 3,
					'wrapper' => ['width' => '70'],
				],

			],
		]);
	}
);