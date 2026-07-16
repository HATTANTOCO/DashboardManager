<?php
/**
 * DashboardManagerService.php
 *
 * DashboardManagerプラグイン
 *
 * @package    DashboardManager
 * @subpackage Service\Admin
 * @author     HATTA
 * @license    MIT License
 * @link       https://hattantoco.com
 */
declare(strict_types=1);

namespace DashboardManager\Service\Admin;

use Cake\Core\Configure;
use BaserCore\Service\Admin\DashboardAdminService as BaseDashboardAdminService;

class DashboardManagerService extends BaseDashboardAdminService
{
    /**
     * ダッシュボード表示用データの動的カスタマイズ
     *
     * @param int $logNum ログの表示件数
     * @return array カスタマイズ済みのビュー変数配列
     */
    public function getViewVarsForIndex(int $logNum): array
    {
        // 1. コアの元の処理を動かしてベースデータを取得
        $viewVars = parent::getViewVarsForIndex($logNum);

        // 2. コアシステムに登録した「非表示ターゲット一覧」を設定ファイルから動的に取得
        $hideTargets = Configure::read('BcApp.dashboard.hidePanels', []);

        if (is_array($hideTargets) && !empty($hideTargets)) {
            foreach ($hideTargets as $target) {
                // 通信データの自動無効化
                // 登録されたパネル名（例: baser_news）から、自動通信データ（例: baserNews）を算出して空にする
                $camelTarget = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $target))));
                if (isset($viewVars[$camelTarget])) $viewVars[$camelTarget] = [];
                if (isset($viewVars[$target])) $viewVars[$target] = [];

                // パネル枠の消去
                // システムが自動収集した全パネル配列の中から、setting.phpに登録されたターゲットパネル枠をすべて消去
                if (isset($viewVars['panels'])) {
                    foreach ($viewVars['panels'] as $plugin => $templates) {
                        if (is_array($templates)) {
                            $key1 = array_search($target, $viewVars['panels'][$plugin]);
                            if ($key1 !== false) unset($viewVars['panels'][$plugin][$key1]);
                            
                            $key2 = array_search($camelTarget, $viewVars['panels'][$plugin]);
                            if ($key2 !== false) unset($viewVars['panels'][$plugin][$key2]);
                        }
                    }
                }
            }
        }

        // 3. 自作のテーマ（BcThemeSample）のパネルを最上位へ並び替えて先頭に移動
        if (isset($viewVars['panels']['BcThemeSample'])) {
            $currentThemePanels = $viewVars['panels']['BcThemeSample'];
            unset($viewVars['panels']['BcThemeSample']);

            // 設定ファイルから「並び順の指定」を取得
            $sortOrder = Configure::read('BcApp.dashboard.sortPanels', []);
            $sortedThemePanels = [];

            if (is_array($sortOrder) && !empty($sortOrder)) {
                foreach ($sortOrder as $panelName) {
                    if (in_array($panelName, $currentThemePanels)) {
                        $sortedThemePanels[] = $panelName;
                        $key = array_search($panelName, $currentThemePanels);
                        unset($currentThemePanels[$key]);
                    }
                }
                if (!empty($currentThemePanels)) {
                    $sortedThemePanels = array_merge($sortedThemePanels, $currentThemePanels);
                }
            } else {
                $sortedThemePanels = $currentThemePanels;
            }

            // ソートした自作テーマの配列を一番先頭にして、全体の配列を再結合
            $viewVars['panels'] = array_merge(
                ['BcThemeSample' => $sortedThemePanels],
                $viewVars['panels']
            );
        }

        return $viewVars;
    }
}
