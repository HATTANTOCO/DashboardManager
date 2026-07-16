<?php
/**
 * setting.php
 *
 * DashboardManagerプラグイン設定ファイル
 *
 * @package    DashboardManager
 * @subpackage Config
 * @author     HATTA
 * @license    MIT License
 * @link       https://hattantoco.com
 */
return [
    'BcApp' => [
        'dashboard' => [
            // 非表示設定：パネル非表示＆通信を遮断したいパネルのファイル名（識別子）を登録する
            'hidePanels' => [
                'baser_news',    // baserCMSニュースパネルを非表示
                // 'mail_messages', // 受信メールパネルを非表示
                // 'contents_info', // コンテンツ情報パネルを非表示
                // 'update_log',    // 最近の動きパネルを非表示
            ],
            // 並び替え設定：先頭に配置したい自作パネル名から順に登録する
            'sortPanels' => [
                'sample_my_panel', // 1番目に表示したい自作パネル名
                // 'sample_my_panel2', // 2番目に表示したい自作パネル名
            ]
        ]
    ]
];
