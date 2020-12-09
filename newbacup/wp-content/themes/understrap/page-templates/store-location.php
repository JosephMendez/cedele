<?php
/**
 * Template Name: Location & Menu Layout
 *
 * @package UnderStrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();
$container = get_theme_mod( 'understrap_container_type' );
$master_data = get_master_data();
$districts = array_filter($master_data, function($item) { return $item->type == 'district'; });
$areas = array_filter($master_data, function($item) { return $item->type == 'area'; });
$outlets = array_filter($master_data, function($item) { return $item->type == 'outlet'; });
$last_time_order = get_option('last_time_order');
?>

<div class="wrapper m-0 pt-0" id="page-wrapper">
  <?php if (have_posts()) {
    while (have_posts()) {
      the_post();
      the_content();
    }
  }?>
  <div class="<?php echo esc_attr( $container ); ?> mt-4 mt-sm-5" id="content">

    <?php
    the_title(
      sprintf( '<h1 class="cdl-heading">', esc_url( get_permalink() ) ),
      '</h1>'
    );
    ?>
	  <?php  if ($outlets)
	  foreach ($outlets as $row) {
	  ?>
	  <h3 class="h3-title h3-title<?php echo $row->id?>"><?php echo $row->data_description?></h3>
	  <?php } ?>
    <div class="row row-xs mt-4 mt-sm-5">
      <div class="col-lg-4">
        <div class="filter-box">
          <h4 class="cdl-heading">Filter by</h4>
          <form>
            <div class="row row-xs">
              <div class="col-7">
                <div class="form-group position-relative">
                  <label class="form-label mb-0 cdl-subtitle">Area</label>
                  <input autocomplete="off" id="suggestion-input" type="text" class="form-control suggestion-input store-location-suggest-input" placeholder="" />
                  <div class="suggestion-select">
                    <ul class="list-unstyled">
                      <li class="modal-toggle" data-selection="district">Select Zone</li>
                      <li class="modal-toggle" data-selection="area">Select Area</li>
                    </ul>
                  </div>
<!--                  <svg id="reset-filter" class="icon icon-X-mark d-none">-->
<!--                    <use xlink:href="--><?php //echo get_stylesheet_directory_uri()?><!--/assets/symbol/sprite.svg#X-mark"></use>-->
<!--                  </svg>-->
                </div>
              </div>
              <div class="col-5">
                <div class="form-group">
                  <label class="form-label mb-0 cdl-subtitle">Concept</label>
                  <select id="select-outlet" class="form-control custom-select">
                    <option value="all">All Outlets</option>
                    <?php
                      if ($outlets){
                        foreach($outlets as $key=>$outlet){
                    ?>
                      <option value="<?php echo $outlet->id;?>"><?php echo $outlet->data_name;?></option>
                    <?php
                        }
                      }
                    ?>
                  </select>
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="store-list">
          <h4 class="cdl-heading">List of Outlets</h4>
          <div class="store-list-wrapper cdl-scrollable">
          </div>
        </div>
      </div>
      <div class="col-lg-8">
        <div id="map-container"></div>
      </div>
    </div>

  </div><!-- #content -->

</div><!-- #page-wrapper -->

<div id="location-modal" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="district-tab" data-toggle="tab" href="#district" role="tab" aria-controls="district" aria-selected="true">
              <h4 class="cdl-heading mb-0">District</h4>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="area-tab" data-toggle="tab" href="#area" role="tab" aria-controls="area" aria-selected="false">
              <h4 class="cdl-heading mb-0">Area</h4>
            </a>
          </li>
        </ul>
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane show clearfix active" id="district" role="tabpanel" aria-labelledby="district-tab">
            <div class="custom-control custom-checkbox w-100 mb-3">
              <input type="checkbox" name="select-all-districts" class="custom-control-input" id="allDistricts" data-type="select-all" checked>
              <label class="custom-control-label" for="allDistricts">Select All</label>
            </div>
            <?php
              if ($districts){
                foreach($districts as $key=>$district){
            ?>
              <div class="custom-control custom-checkbox">
                <input type="checkbox" name="selected_district[]" class="custom-control-input" id="customCheck<?php echo $district->id ?>" value="<?php echo $district->id ?>" data-name="<?php echo $district->data_name ?>" data-type="select-option" checked>
                <label class="custom-control-label" for="customCheck<?php echo $district->id ?>"><?php echo $district->data_name ?></label>
              </div>
            <?php
                }
              }
            ?>
          </div>
          <div class="tab-pane clearfix" id="area" role="tabpanel" aria-labelledby="area-tab">
            <div class="custom-control custom-checkbox w-100 mb-3">
              <input type="checkbox" name="select-all-areas" class="custom-control-input" id="allAreas" data-type="select-all" checked>
              <label class="custom-control-label" for="allAreas">Select All</label>
            </div>
            <?php
              if ($areas){
                foreach($areas as $key=>$area){
            ?>
              <div class="custom-control custom-checkbox">
                <input type="checkbox" name="selected_area[]" class="custom-control-input" id="customCheck<?php echo $area->id ?>" value="<?php echo $area->id ?>" data-name="<?php echo $area->data_name ?>" data-type="select-option" checked>
                <label class="custom-control-label" for="customCheck<?php echo $area->id ?>"><?php echo $area->data_name ?></label>
              </div>
            <?php
                }
              }
            ?>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <a href="#" class="heading-font" id="btn-apply">Apply</a>
        <a href="#" class="heading-font btn-clear" id="btn-clear">Clear Selection</a>
      </div>
    </div>
  </div>
</div>

<script type="text/template" id="store-template">
  <div class="store-card {0}"
    data-id="{1}"
    data-name="{2}"
    data-long="{3}"
    data-lat="{4}"
    data-number_house="{5}"
    data-street_name="{6}"
    data-floor_unit="{7}"
    data-building="{8}"
    data-district="{9}"
    data-area="{10}"
    data-phone_number="{11}"
    data-img="{12}"
    data-file="{13}"
    data-zipcode="{19}"
    last-order="{20}"
	data-outlet_type="{21}"
  >
  <h6 class="cdl-heading text-primary pr-5">{14}</h6>
  <a href="{15}" target="_blank" class="heading-font menu-link">MENU</a>
  <ul class="list-unstyled mb-0 store-info">
    <li>
      <svg class="icon icon-Map">
        <use xlink:href="<?php echo get_stylesheet_directory_uri() ?>/assets/symbol/sprite.svg#Map"></use>
      </svg>
      <p class="mb-0">{16}</p>
    </li>
    <li>
      <svg class="icon icon-Phone">
        <use xlink:href="<?php echo get_stylesheet_directory_uri() ?>/assets/symbol/sprite.svg#Phone"></use>
      </svg>
      <p class="mb-0">{17}</p>
    </li>
    <li>
      <svg class="icon icon-Clock">
        <use xlink:href="<?php echo get_stylesheet_directory_uri() ?>/assets/symbol/sprite.svg#Clock"></use>
      </svg>{18}</li>
    <li>
      {20}
    </li>
  </ul>
</div>
</script>

<script type="text/template" id="store-overlay-template">
  <div class="store-card overlay">
    <img src="{3}" alt="Store Image" class="store-image" />
    <div class="position-relative p-3">
      <h6 class="cdl-heading text-primary pr-5">{0}</h6>
      <a href="{4}" target="_blank" class="heading-font menu-link">MENU</a>
      <ul class="list-unstyled mb-0 store-info">
        <li>
          <svg class="icon icon-Map">
            <use xlink:href="<?php echo get_stylesheet_directory_uri() ?>/assets/symbol/sprite.svg#Map"></use>
          </svg>
          <p class="mb-0">{1}</p>
        </li>
        <li>
          <svg class="icon icon-Phone">
            <use xlink:href="<?php echo get_stylesheet_directory_uri() ?>/assets/symbol/sprite.svg#Phone"></use>
          </svg>
          <p class="mb-0">{2}</p>
        </li>
      </ul>
    </div>
  </div>
</script>

<?php
get_footer();
