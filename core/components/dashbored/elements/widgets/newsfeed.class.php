<?php

require_once __DIR__ . '/abstract.class.php';

class DashboredNewsFeedDashboardWidget extends DashboredAbstractDashboardWidget
{
    // Values are defaults
    public const ACCEPTED_FIELDS = [];
    
    public function render(): string
    {
        $this->initialize();

        $this->controller->addCss($this->dashbored->config['assets_url'] . 'css/mgr.css');

        $titleBar = $this->getWidgetTitleBar('News');
        $this->widget->set('name', $titleBar);

        $props = [];
        foreach (self::ACCEPTED_FIELDS as $field => $default) {
            $props[$field] = self::getUserSetting($this->modx, 'dashbored.newsfeed.' . $field,
                    $this->modx->user->get('id')) ?? $default;
        }
        
        $this->controller->addHtml(<<<HTML
<script src="{$this->dashbored->config['assets_url']}quotes/newsfeed.js"></script>
<script>
Ext.onReady(function() {
    new DashboredNewsFeed('{$this->widget->get('id')}').setup();
});
</script>

HTML
        );

        return <<<HTML
<div class="dashbored-newsfeed-mask">
    <svg class="dashbored-spinner" viewBox="0 0 50 50">
      <circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5"></circle>
    </svg>
</div>
<div class="dashbored-bg">
    <div class="db-bg-mask"></div>
</div>
<div id="dashbored{$this->widget->get('id')}-newsfeed" class="dashbored-newsfeed-widget" 
    data-id="{$this->widget->get('id')}"
    data-backgroundtype="{$props['background_type']}" 
    data-backgroundmask="{$props['bg_mask']}" 
>
    <blockquote class="quote"></blockquote>
</div>
HTML;
    }
}
return 'DashboredNewsFeedDashboardWidget';