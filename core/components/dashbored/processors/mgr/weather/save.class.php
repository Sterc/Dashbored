<?php

use MODX\Revolution\modDashboardWidget;

class DashboredWeatherSaveProcessor extends modProcessor {

    protected $dashbored;

    /**
     * @return string[]
     */
    public function getLanguageTopics(): array
    {
        return ['dashbored:default'];
    }

    /**
     * @return bool
     */
    public function initialize(): bool
    {
        $corePath = $this->modx->getOption('dashbored.core_path', null,
            $this->modx->getOption('core_path') . 'components/dashbored/');
        $this->dashbored = $this->modx->getService('dashbored', 'Dashbored', $corePath . 'model/dashbored/');

        return true;
    }

    public function process()
    {
        if (!$id = $this->getProperty('id')) {
            return $this->failure('Cannot find widget id.');
        }

        /** @var modDashboardWidget $widget */
        $widget = $this->modx->getObject(modDashboardWidget::class, [
            'id' => $id
        ]);
        if (!$widget) {
            return $this->failure('Cannot find weather widget.');
        }
        
        $properties = [];

        $location = $this->getProperty('location');
        $properties['location'] = $this->getProperty('location')
            ? filter_var($location, FILTER_SANITIZE_STRING)
            : $this->modx->getOption('dashbored.weather.default_city', '', 'amsterdam', true);

        // 'c' or 'f'
        $tempType = $this->getProperty('temp_type');
        $properties['temp_type'] = $tempType ? filter_var($tempType, FILTER_SANITIZE_STRING) : 'c';

        // 'km' or 'mile'
        $windType = $this->getProperty('wind_type');
        $properties['distance_type'] = $windType ? filter_var($windType, FILTER_SANITIZE_STRING) : 'km';
        
        $widget->set('properties', $properties);
        $widget->save();

        return $this->success('', $widget);
    }
}
return 'DashboredWeatherSaveProcessor';