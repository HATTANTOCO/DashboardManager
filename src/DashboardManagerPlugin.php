<?php
/**
 * Class DashboardManagerPlugin
 *
 * DashboardManagerプラグイン
 * 
 * 設定ファイル（setting.php）の定義に基づいて、ダッシュボードの特定パネルの非表示およびそれに紐づく外部通信データを遮断
 *
 * @package    DashboardManager
 * @subpackage 
 * @author     HATTA
 * @license    MIT License
 * @link       https://hattantoco.com
 */
declare(strict_types=1);

namespace DashboardManager;

use Cake\Core\ContainerInterface;
use BaserCore\BcPlugin;
use BaserCore\Service\Admin\DashboardAdminServiceInterface;
use DashboardManager\Service\Admin\DashboardManagerService;

class DashboardManagerPlugin extends BcPlugin
{
    /* プラグインのインストール（有効化）処理 */
    public function install($options = []): bool
    {
        return parent::install($options);
    }

    /* プラグインのアクティベート（有効化完了）処理 */
    public function activate($options = []): bool
    {
        return parent::activate($options);
    }

    /**
     * サービスコンテナの入れ替え（依存性注入）処理 */
    public function services(ContainerInterface $container): void
    {
        parent::services($container);

        // DashboardManagerServiceをDIコンテナにバインド
        $container->add(DashboardAdminServiceInterface::class, DashboardManagerService::class);
    }
}
