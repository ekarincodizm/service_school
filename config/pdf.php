<?php


return [ 
		'mode' => '',
		'format' => 'A4',
		'custom_font_path' => base_path('/public/assets/fonts/'), // don't forget the trailing slash!
		'custom_font_data' => [
				'sarabunnew' => [
						'R'  => 'THSarabunNew/THSarabunNew.ttf',    // regular font
						'B'  => 'THSarabunNew/THSarabunNew Bold.ttf',       // optional: bold font
						'I'  => 'THSarabunNew/THSarabunNew Italic.ttf',     // optional: italic font
						'BI' => 'THSarabunNew/THSarabunNew BoldItalic.ttf' // optional: bold-italic font
				],
				'sarabunpsk' => [
						'R'  => 'THSarabunPSK-Ver2/THSarabun.ttf',    // regular font
						'B'  => 'THSarabunPSK-Ver2/THSarabun Bold.ttf',       // optional: bold font
						'I'  => 'THSarabunPSK-Ver2/THSarabun Italic.ttf',     // optional: italic font
						'BI' => 'THSarabunPSK-Ver2/THSarabun Bold Italic.ttf' // optional: bold-italic font
		     	]
		],
		'default_font_size'    => '12',
		'default_font'         => 'sarabunnew',
		'margin_left'          => 10,
		'margin_right'         => 10,
		'margin_top'           => 10,
		'margin_bottom'        => 10,
		'margin_header'        => 0,
		'margin_footer'        => 0,
		'title' => 'Test Title',
		'author' => 'Test Author',
		'watermark' => '',
		'show_watermark' => false,
		'watermark_font' => 'sans-serif',
		'display_mode' => 'fullpage',
		'watermark_text_alpha' => 0.1 ,
		'use_kwt' => 'true'
];