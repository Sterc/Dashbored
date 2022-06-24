<?php

require_once __DIR__ . '/abstract.class.php';

class DashboredSiteDashMonitorDashboardWidget extends DashboredAbstractDashboardWidget
{
    // Values are defaults
    public const ACCEPTED_FIELDS = [];

    public function render(): string
    {
        $this->initialize();

        $titleBar = $this->getWidgetTitleBar('sitedash_monitor');
        $this->widget->set('name', $titleBar);

        $props = [];
        foreach (self::ACCEPTED_FIELDS as $field => $default) {
            $props[$field] = self::getUserSetting($this->modx, 'dashbored.sitedash_monitor.' . $field,
                    $this->modx->user->get('id')) ?? $default;
        }

        $this->controller->addHtml(<<<HTML
<style>
    #dashboard-block-{$this->widget->get('id')} {
        background: rgb(63,91,123);
        background: linear-gradient(120deg, rgba(90,114,142,1) 0%, rgba(63,91,123,1) 18%, rgba(35,67,104,1) 54%);
    }
    #dashboard-block-{$this->widget->get('id')} .body {
        box-shadow: inset 0 1px 1px 0 rgb(0 0 0/.2);
    }
    #dashboard-block-{$this->widget->get('id')} .title-wrapper {background: #fff;}
</style>
<script src="{$this->dashbored->config['assets_url']}node_modules/chart.js/dist/chart.min.js"></script>
<script src="{$this->dashbored->config['assets_url']}sitedash/sitedash-monitor.js"></script>
<script>
Ext.onReady(function() {
    new DashboredSiteDashMonitor('{$this->widget->get('id')}').setup();
});
</script>

HTML
        );

        return <<<HTML
<div class="dashbored-sitedash-monitor-mask">
    <svg class="dashbored-spinner" viewBox="0 0 50 50">
      <circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5"></circle>
    </svg>
</div>
<div class="dashbored-bg">
    <div class="db-bg-mask"></div>
</div>
<div class="dashbored-sitedash-header"></div>
<div id="dashbored{$this->widget->get('id')}-sitedash-monitor" class="dashbored-sitedash-monitor-widget" 
    data-id="{$this->widget->get('id')}"
>

    <div class="dashbored-sitedash-monitor-chart">
        <canvas id="dashbored-sitedash-uptime" width="400" height="120"></canvas>
    </div>
</div>
HTML;
    }
}
return 'DashboredSiteDashMonitorDashboardWidget';