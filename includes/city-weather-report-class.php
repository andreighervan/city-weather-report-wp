<?php
class City_Weather_Report_Widget extends WP_Widget {

    /**
     * Sets up the widgets name etc
     */
    public function __construct() {
        $widget_ops = array(

            'description' => __('Simple weather widget','cwr_domain'),
        );
        parent::__construct( 'city_weather_report_widget', __('City Weather Report','cwr_domain'), $widget_ops );
    }

    /**
     * Outputs the content of the widget
     *
     * @param array $args
     * @param array $instance
     */
    public function widget( $args, $instance ) {
        // outputs the content of the widget
        $city=$instance['city'];
        $state=$instance['state'];
        $options=array(
            'use_geolocation'=>$instance['use_geolocation']?true:false,
            'show_humidity'=>$instance['show_humidity']?true:false,
            'temp_type'=>$instance['temp_type']
        );
        echo $args['before_widget'];
        echo $this->getWeather($city,$state,$options);
        echo $args['after_widget'];
    }

    /**
     * Outputs the options form on admin
     *
     * @param array $instance The widget options
     */
    public function form( $instance ) {
        // outputs the options form on admin
        $city=$instance['city'];
        $state=$instance['state'];
        $use_geolocation=$instance['use_geolocation'];
        $show_humidity=$instance['show_humidity'];
        $temp_type=$instance['temp_type'];
        ?><p>
<input class="checkbox" type="checkbox" <?php checked($instance['use_geolocation'],'on');?> id="<?php echo $this->get_field_id('use_geolocation');?>" name="<?php echo $this->get_field_name('use_geolocation');?>"    />
<label for="<?php echo $this->get_field_id('use_geolocation');?>">Use Geolocation</label></p>
        <p>
            <label for="<?php echo $this->get_field_id('city');?>"><?php _e('City','cwr_domain');?></label>
            <input class="widefat" type="text" id="<?php echo $this->get_field_id('city');?>" name="<?php echo $this->get_field_name('city');?>" value="<?php echo esc_attr($city);?>"   />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('state');?>"><?php _e('State','cwr_domain');?></label>
            <input class="widefat" type="text" id="<?php echo $this->get_field_id('state');?>" name="<?php echo $this->get_field_name('state');?>" value="<?php echo esc_attr($state);?>"   />
        </p>
        <p>
        <label for="<?php echo $this->get_field_id('temp_type');?>"><?php _e('Temperature type','cwr_domain');?></label>
        <select class="widefat" id=""<?php echo $this->get_field_id('temp_type');?>" name="<?php echo $this->get_field_name('temp_type');?>">
        <option value="Fahrenheit" <?php echo ($temp_type=='Fahrenheit')?'selected' :'';?>>Fahrenheit</option>
        <option value="Celsius" <?php echo ($temp_type=='Celsius')?'selected' :'';?>>Celsius</option>
        <option value="Both" <?php echo ($temp_type=='Both')?'selected' :'';?>>Both</option>
        </select></p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($instance['show_humidity'],'on');?> id="<?php echo $this->get_field_id('show_humidity');?>" name="<?php echo $this->get_field_name('show_humidity');?>"    />
            <label for="<?php echo $this->get_field_id('show_humidityn');?>">Show Humidity</label></p>
<?php
}

    /**
     * Processing widget options on save
     *
     * @param array $new_instance The new options
     * @param array $old_instance The previous options
     */
    public function update( $new_instance, $old_instance ) {
        // processes widget options to be saved
        $instance=array(
            'title'=>(!empty($new_instance['title']))?strip_tags($new_instance['title']):'',
            'city'=>(!empty($new_instance['city']))?strip_tags($new_instance['city']):'',
            'state'=>(!empty($new_instance['state']))?strip_tags($new_instance['state']):'',
            'use_geolocation'=>(!empty($new_instance['use_geolocation']))?strip_tags($new_instance['use_geolocation']):'',
            'show_humidity'=>(!empty($new_instance['show_humidity']))?strip_tags($new_instance['show_humidity']):'',
            'temp_type'=>(!empty($new_instance['temp_type']))?strip_tags($new_instance['temp_type']):''
        );
        return $instance;
    }
    public function getWeather($city,$state,$options){
        $geoplugin=new geoPlugin();
        $geoplugin->locate();
        if($options['use_geolocation']){
            $city=$geoplugin->city;
            $state->$geoplugin->region;
        }
        $json_string=file_get_contents("http://api.wunderground.com/api/...");
        $parsed_json=json_decode($json_string);
        $location=$parsed_json->{'location'}->{'city'}.', '.$parsed_json->{'location'}->{'country'};
        $weather=$parsed_json->{'current_observation'}->{'weather'};
        $icon_url=$parsed_json->{'current_observation'}->{'icon_url'};
        $temp_f=$parsed_json->{'current_observation'}->{'temp_f'};
        $temp_c=$parsed_json->{'current_observation'}->{'temp_c'};
        $relative_humidity=$parsed_json->{'cuurrent_observation'}->{'relative_humidity'};
        ?>
<div class="city-weather">
    <h3><?php echo ${location};?></h3>
    <?php if($options['temp_type']=='Fahrenheit'):?>

    <h1><?php echo ${temp_f};?> °F</h1>
    <?php elseif($options['temp_type']=='Celsius'):?>
    <h1><?php echo ${temp_c};?> °C</h1>
    <?php else:?>
        <h1><?php echo ${temp_f};?> °F(<?php echo ${temp_c};?> °C)</h1>
    <?php endif;?>
    <?php echo ${weather};?>
    <img src="<?php echo ${icon_url};?>">
    <?php if($options['show_humidity']):?>
    <div><strong>Relative Humidity <?php echo ${relative_humidity};?></strong></div>
    <?php endif;?>
</div>
<?php
    }
}
