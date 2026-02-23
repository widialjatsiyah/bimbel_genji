<style type="text/css">
    .img-home {
        margin-top: 1rem;
        width: <?= isset($app->dashboard_image_width) ? $app->dashboard_image_width . '%' : '100%' ?>;
        max-height: <?= isset($app->dashboard_image_max_height) ? $app->dashboard_image_max_height . 'px' : '450px' ?>;
        object-fit: <?= isset($app->dashboard_image_object_fit) ? $app->dashboard_image_object_fit : 'cover' ?>;
        object-position: <?= isset($app->dashboard_image_object_position) ? $app->dashboard_image_object_position : 'center' ?>;
        box-shadow: <?= (isset($app->dashboard_image_box_shadow) && $app->dashboard_image_box_shadow === '1') ? '0 1px 2px rgba(0, 0, 0, 0.1)' : 'none' ?>;
    }

    .text-small {
        font-size: 1rem;
        display: block;
        color: rgba(255, 255, 255, .8);
        font-weight: 600;
    }

    .flot-chart--xs {
        color: rgba(255, 255, 255, 0.6);
        font-size: 1.2rem;
        text-align: center;
        text-shadow: 0px 1px rgba(1, 1, 1, 0.1);
        font-weight: 500;
    }

    .stats__info h2 {
        font-size: 1.1rem;
        font-weight: 300;
    }

    @media only screen and (max-width: 768px) {
        .img-home {
            margin-top: 1rem;
            width: 100%;
            max-height: <?= isset($app->dashboard_image_max_height) ? $app->dashboard_image_max_height . 'px' : '450px' ?>;
            object-fit: <?= isset($app->dashboard_image_object_fit) ? $app->dashboard_image_object_fit : 'cover' ?>;
            object-position: <?= isset($app->dashboard_image_object_position) ? $app->dashboard_image_object_position : 'center' ?>;
            box-shadow: <?= (isset($app->dashboard_image_box_shadow) && $app->dashboard_image_box_shadow === '1') ? '0 1px 2px rgba(0, 0, 0, 0.1)' : 'none' ?>;
        }
    }
</style>

<div class="card">
    <div class="card-body">
        <h4 class="card-title">Beranda</h4>
        <h6 class="card-subtitle">Selamat datang di <?= $app->app_name ?> v<?= $app->app_version ?></h6>

        <!-- DASHBOARD IMAGE -->
        <?php if (isset($app->dashboard_image_source) && !empty($app->dashboard_image_source)) : ?>
            <div class="row">
                <div class="col">
                    <center>
                        <img src="<?php echo base_url($app->dashboard_image_source) ?>" class="img-home" />
                    </center>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>