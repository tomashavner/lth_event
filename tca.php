<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_lthevents_event'] = array(
	'ctrl' => $TCA['tx_lthevents_event']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid,l10n_parent,l10n_diffsource,hidden,event,start,end,description,place,organizer,signup'
	),
	'feInterface' => $TCA['tx_lthevents_event']['feInterface'],
	'columns' => array(
		'sys_language_uid' => array(		
			'exclude' => 1,
			'label'  => 'LLL:EXT:lang/locallang_general.xml:LGL.language',
			'config' => array(
				'type'                => 'select',
				'foreign_table'       => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xml:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.default_value', 0)
				)
			)
		),
		'l10n_parent' => array(		
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude'     => 1,
			'label'       => 'LLL:EXT:lang/locallang_general.xml:LGL.l18n_parent',
			'config'      => array(
				'type'  => 'select',
				'items' => array(
					array('', 0),
				),
				'foreign_table'       => 'tx_lthevents_event',
				'foreign_table_where' => 'AND tx_lthevents_event.pid=###CURRENT_PID### AND tx_lthevents_event.sys_language_uid IN (-1,0)',
			)
		),
		'l10n_diffsource' => array(		
			'config' => array(
				'type' => 'passthrough'
			)
		),
		'hidden' => array(		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array(
				'type'    => 'check',
				'default' => '0'
			)
		),
		'event' => array(		
			'exclude' => 0,		
			'label' => 'LLL:EXT:lth_events/locallang_db.xml:tx_lthevents_event.event',		
			'config' => array(
				'type' => 'input',	
				'size' => '30',	
				'eval' => 'required,trim',
			)
		),
		'start' => array(		
			'exclude' => 0,		
			'label' => 'LLL:EXT:lth_events/locallang_db.xml:tx_lthevents_event.start',		
			'config' => array(
				'type'     => 'input',
				'size'     => '12',
				'max'      => '20',
				'eval'     => 'datetime',
				'checkbox' => '0',
				'default'  => '0'
			)
		),
		'end' => array(		
			'exclude' => 0,		
			'label' => 'LLL:EXT:lth_events/locallang_db.xml:tx_lthevents_event.end',		
			'config' => array(
				'type'     => 'input',
				'size'     => '12',
				'max'      => '20',
				'eval'     => 'datetime',
				'checkbox' => '0',
				'default'  => '0'
			)
		),
		'description' => array(		
			'exclude' => 0,		
			'label' => 'LLL:EXT:lth_events/locallang_db.xml:tx_lthevents_event.description',		
			'config' => array(
				'type' => 'text',
				'cols' => '30',
				'rows' => '5',
				'wizards' => array(
					'_PADDING' => 2,
					'RTE' => array(
						'notNewRecords' => 1,
						'RTEonly'       => 1,
						'type'          => 'script',
						'title'         => 'Full screen Rich Text Editing|Formatteret redigering i hele vinduet',
						'icon'          => 'wizard_rte2.gif',
						'script'        => 'wizard_rte.php',
					),
				),
			)
		),
		'place' => array(		
			'exclude' => 0,		
			'label' => 'LLL:EXT:lth_events/locallang_db.xml:tx_lthevents_event.place',		
			'config' => array(
				'type' => 'input',	
				'size' => '30',	
				'eval' => 'trim',
			)
		),
		'organizer' => array(		
			'exclude' => 0,		
			'label' => 'LLL:EXT:lth_events/locallang_db.xml:tx_lthevents_event.organizer',		
			'config' => array(
				'type' => 'input',	
				'size' => '30',	
				'eval' => 'trim',
			)
		),
		'signup' => array(		
			'exclude' => 0,		
			'label' => 'LLL:EXT:lth_events/locallang_db.xml:tx_lthevents_event.signup',		
			'config' => array(
				'type' => 'check',
			)
		),
	),
	'types' => array(
		'0' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, event, start, end, description;;;richtext[]:rte_transform[mode=ts], place, organizer, signup')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	)
);
?>